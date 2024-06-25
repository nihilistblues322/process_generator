<?php

namespace src\Database;

use PDO;
use src\Process;
use src\Database\Database;
use src\Fields\FieldInterface;

class ProcessRepository
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function saveProcess(Process $process): void
    {
        $sql = "SELECT id FROM processes WHERE name = :name";
        $stmt = $this->db->query($sql, ['name' => $process->name]);
        $existingProcess = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingProcess) {
            echo "Process with name '{$process->name}' already exists.\n";
            return;
        }
        $sql = "INSERT INTO processes (name) VALUES (:name)";
        $this->db->query($sql, ['name' => $process->name]);
        $processId = $this->db->query("SELECT last_insert_rowid()")->fetchColumn();

        $process->id = $processId;

        foreach ($process->getFields() as $field) {
            $this->saveField($processId, $field);
        }
    }

    private function saveField($processId, FieldInterface $field): void
    {
        $sql = "INSERT INTO fields (process_id, name, type_id, value, format) 
                VALUES (:process_id, :name, (SELECT id FROM field_types WHERE name = :type), :value, :format)";
        $this->db->query($sql, [
            'process_id' => $processId,
            'name' => $field->getName(),
            'type' => $field->getType(),
            'value' => $field->getValue(),
            'format' => $field->getFormat()
        ]);
    }

    public function getProcesses($limit, $offset): array
    {
        $sql = "SELECT * FROM processes LIMIT :limit OFFSET :offset";
        $stmt = $this->db->query($sql, ['limit' => $limit, 'offset' => $offset]);
        return $stmt->fetchAll();
    }
}
