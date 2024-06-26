<?php

namespace src\Database;

use PDO;
use DateTime;
use src\Process;
use src\Fields\DateField;
use src\Fields\TextField;

use src\Fields\NumberField;
use src\Fields\Interfaces\IField;


class ProcessRepository
{
    private PDO $db;

    public function __construct(string $dbPath)
    {
        $this->db = new PDO("sqlite:$dbPath");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->createTables();
    }

    private function createTables(): void
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS processes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT UNIQUE NOT NULL
            );
            CREATE TABLE IF NOT EXISTS fields (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                process_id INTEGER,
                name TEXT NOT NULL,
                type TEXT NOT NULL,
                value TEXT,
                format TEXT,
                FOREIGN KEY (process_id) REFERENCES processes(id),
                UNIQUE (process_id, name)
            );
        ");
    }

    public function save(Process $process): void
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("INSERT OR REPLACE INTO processes (id, name) VALUES (:id, :name)");
            $stmt->execute([
                'id' => $process->getId(),
                'name' => $process->getName()
            ]);

            $processId = $process->getId() ?? $this->db->lastInsertId();

            foreach ($process->getFields() as $field) {
                $stmt = $this->db->prepare("
                    INSERT OR REPLACE INTO fields (process_id, name, type, value, format)
                    VALUES (:process_id, :name, :type, :value, :format)
                ");
                $stmt->execute([
                    'process_id' => $processId,
                    'name' => $field->getName(),
                    'type' => $field->getType(),
                    'value' => $field instanceof DateField ? $field->getValue()->format('Y-m-d') : $field->getValue(),
                    'format' => $field instanceof NumberField || $field instanceof DateField ? $field->getFormat() : null
                ]);
            }

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getById(int $id): ?Process
    {
        $stmt = $this->db->prepare("SELECT * FROM processes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $processData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$processData) {
            return null;
        }

        $process = new Process($processData['name'], $processData['id']);

        $stmt = $this->db->prepare("SELECT * FROM fields WHERE process_id = :process_id");
        $stmt->execute(['process_id' => $id]);
        $fieldsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($fieldsData as $fieldData) {
            $field = $this->createField($fieldData);
            $process->addField($field);
        }

        return $process;
    }

    public function getAll(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare("SELECT * FROM processes LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $processesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $processes = [];

        foreach ($processesData as $processData) {
            $processes[] = $this->getById($processData['id']);
        }

        return $processes;
    }

    private function createField(array $fieldData): IField
    {
        switch ($fieldData['type']) {
            case 'text':
                return new TextField($fieldData['name'], $fieldData['value']);
            case 'number':
                return new NumberField($fieldData['name'], (float)$fieldData['value'], $fieldData['format']);
            case 'date':
                return new DateField($fieldData['name'], new DateTime($fieldData['value']), $fieldData['format']);
            default:
                throw new \InvalidArgumentException("Unknown field type: {$fieldData['type']}");
        }
    }
}