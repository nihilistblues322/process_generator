<?php

use src\Database\Database;

require 'vendor/autoload.php';

$path = __DIR__ . '/database.sqlite';

$db = new Database($path);


$sql = "
CREATE TABLE IF NOT EXISTS field_types (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL
);
";

try {
    $db->query($sql);
    echo "Table 'field_types' created successfully.\n";
} catch (PDOException $e) {
    die("Error creating table 'field_types': " . $e->getMessage());
}


$sql = "
CREATE TABLE IF NOT EXISTS processes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);
";

try {
    $db->query($sql);
    echo "Table 'processes' created successfully.\n";
} catch (PDOException $e) {
    die("Error creating table 'processes': " . $e->getMessage());
}


$sql = "
CREATE TABLE IF NOT EXISTS fields (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    process_id INTEGER,
    name TEXT NOT NULL,
    type_id INTEGER NOT NULL,
    value TEXT,
    format TEXT,
    FOREIGN KEY (process_id) REFERENCES processes(id),
    FOREIGN KEY (type_id) REFERENCES field_types(id)
);
";

try {
    $db->query($sql);
    echo "Table 'fields' created successfully.\n";
} catch (PDOException $e) {
    die("Error creating table 'fields': " . $e->getMessage());
}


$sql = "
INSERT INTO field_types (name) VALUES ('text'), ('number'), ('date');
";

try {
    $db->query($sql);
    echo "Initial data inserted into 'field_types' table.\n";
} catch (PDOException $e) {
    die("Error inserting initial data into 'field_types' table: " . $e->getMessage());
}

echo "Database initialized successfully.\n";
