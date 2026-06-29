<?php
class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../config/database.php';
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            self::$instance = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$instance;
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = [])
    {
        return self::query($sql, $params)->fetch();
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_map(fn($col) => "`$col`", array_keys($data)));
        $placeholders = ':' . implode(', :', array_keys($data));
        self::query("INSERT INTO $table ($columns) VALUES ($placeholders)", $data);
        return (int) self::getInstance()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $params = []): int
    {
        $sets = implode(', ', array_map(fn($col) => "`$col` = :$col", array_keys($data)));
        $params = array_merge($data, $params);
        self::query("UPDATE $table SET $sets WHERE $where", $params);
        return self::query("SELECT ROW_COUNT()")->fetchColumn();
    }

    public static function delete(string $table, string $where, array $params = []): int
    {
        self::query("DELETE FROM $table WHERE $where", $params);
        return self::query("SELECT ROW_COUNT()")->fetchColumn();
    }

    public static function begin(): void
    {
        self::getInstance()->beginTransaction();
    }

    public static function commit(): void
    {
        self::getInstance()->commit();
    }

    public static function rollback(): void
    {
        self::getInstance()->rollBack();
    }
}
