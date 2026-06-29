<?php
class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM " . static::$table);
    }

    public static function find(int $id)
    {
        return Database::fetch(
            "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?",
            [$id]
        );
    }

    public static function where(string $column, $value): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . static::$table . " WHERE $column = ?",
            [$value]
        );
    }

    public static function whereFirst(string $column, $value)
    {
        return Database::fetch(
            "SELECT * FROM " . static::$table . " WHERE $column = ? LIMIT 1",
            [$value]
        );
    }

    public static function create(array $data): int
    {
        return Database::insert(static::$table, $data);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update(static::$table, $data, static::$primaryKey . " = :id", ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete(static::$table, static::$primaryKey . " = ?", [$id]);
    }

    public static function count(): int
    {
        return (int) Database::query("SELECT COUNT(*) FROM " . static::$table)->fetchColumn();
    }

    public static function paginate(int $page = 1, int $perPage = 10, string $where = '', array $params = []): object
    {
        $whereClause = $where ? "WHERE $where" : '';
        $total = (int) Database::query(
            "SELECT COUNT(*) FROM " . static::$table . " $whereClause", $params
        )->fetchColumn();
        $offset = ($page - 1) * $perPage;
        $items = Database::fetchAll(
            "SELECT * FROM " . static::$table . " $whereClause ORDER BY " . static::$primaryKey . " DESC LIMIT $perPage OFFSET $offset",
            $params
        );
        return (object) [
            'items'      => $items,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'lastPage'   => (int) ceil($total / $perPage),
        ];
    }
}
