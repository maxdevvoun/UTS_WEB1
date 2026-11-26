<?php
require_once __DIR__ . '/app.php';

class Database
{
    private string $host = '127.0.0.1';
    private string $database = 'tripora_db';
    private string $username = 'root';
    private string $password = '';
    private ?PDO $connection = null;

    public function connect(): PDO
    {
        if ($this->connection instanceof PDO) {
            return $this->connection;
        }

        $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";
        $this->connection = new PDO($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return $this->connection;
    }
}
