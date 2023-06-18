<?php

namespace SimpleMvc;

/**
 * Class QueryBuilder
 *
 * This class enables the creation of SQL queries using a fluent interface.
 *
 * @example
 * $queryBuilder = new QueryBuilder('users');
 * $queryBuilder->select()->where('id', '=', 1)->getQuery(); // returns 'SELECT * FROM users WHERE id = ?'
 * $queryBuilder->select()->where('id', '=', 1)->getParams(); // returns [1]
 * $queryBuilder->select()->where('id', '=', 1)->executeQuery()->get(); // returns all users with id = 1
 */
class QueryBuilder
{
    private \PDO $connection;
    private string $table;
    private string $query;
    private array $params;
    private int $paramIndex = 0;

    public function __construct(string $table, \PDO $connection = null)
    {
        if ($connection === null) {
            $this->connection = Database::getInstance()->getConnection();
        }
        $this->table = $table;
    }

    public function setTable(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function setQuery(string $query): static
    {
        $this->query = $query;
        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): static
    {
        $this->params = $params;
        return $this;
    }

    private function reset(): void
    {
        $this->query = '';
        $this->params = [];
        $this->paramIndex = 0;
    }

    private function executeQuery(): \PDOStatement
    {
        $statement = $this->connection->prepare($this->query);
        if (!$statement) {
            throw new \RuntimeException($this->connection->errorInfo()[2]);
        }
        if (!$statement->execute($this->params)) {
            throw new \RuntimeException($statement->errorInfo()[2]);
        }
        $this->reset();
        return $statement;
    }

    public function select(array $columns = ['*']): static
    {
        $this->reset();
        $columns = implode(', ', $columns);
        $this->query = "SELECT $columns FROM {$this->table}";
        return $this;
    }

    public function insert(array $data): int
    {
        $this->reset();
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_map(fn($key) => ":$key", array_keys($data)));
        $this->query = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        $this->params = $data;
        return $this->executeQuery()->rowCount();
    }

    public function update(array $data): static
    {
        $this->reset();
        $columns = implode(', ', array_map(static fn($key) => "$key = :$key", array_keys($data)));
        $this->query = "UPDATE {$this->table} SET $columns";
        $this->params = $data;
        return $this;
    }

    public function delete(): static
    {
        $this->reset();
        $this->query = "DELETE FROM {$this->table}";
        return $this;
    }

    public function where(string $column, string $operator, mixed $value, string $logicOperator = 'AND'): static
    {
        $paramName = 'param' . $this->paramIndex++;
        $this->params[$paramName] = $value;
        if (stripos($this->query, 'WHERE') !== false) {
            $this->query .= " $logicOperator $column $operator :$paramName";
        } else {
            $this->query .= " WHERE $column $operator :$paramName";
        }
        return $this;
    }

    public function whereIn(string $column, array $values, string $logicOperator = 'AND'): static
    {
        $paramNames = [];
        foreach ($values as $value) {
            $paramName = 'param' . $this->paramIndex++;
            $paramNames[] = ":$paramName";
            $this->params[$paramName] = $value;
        }
        $paramNames = implode(', ', $paramNames);
        if (stripos($this->query, 'WHERE') !== false) {
            $this->query .= " $logicOperator $column IN ($paramNames)";
        } else {
            $this->query .= " WHERE $column IN ($paramNames)";
        }
        return $this;
    }

    // Example usage: $qb->select()->join('users', 'users.id', '=', 'entries.user_id')->get();
    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): static
    {
        $this->query .= " $type JOIN $table ON $first $operator $second";
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->query .= " ORDER BY $column $direction";
        return $this;
    }

    public function limit(int $count, int $offset = 0): static
    {
        $this->query .= " LIMIT $offset, $count";
        return $this;
    }

    public function offset(int $offset): static
    {
        $this->query .= " OFFSET $offset";
        return $this;
    }

    public function get(): bool|array
    {
        return $this->executeQuery()->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function first(): bool|array
    {
        return $this->executeQuery()->fetch(\PDO::FETCH_ASSOC);
    }

    public function count(): int
    {
        return $this->executeQuery()->rowCount();
    }

    public function exists(): bool
    {
        return $this->count() > 0;
    }

    public function getCol(): array|bool
    {
        return $this->executeQuery()->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getFieldNames(): array
    {
        $result = $this->first();
        if (!empty($result)) {
            return array_keys($result);
        }
        return [];
    }

    public function getFields(): array
    {
        $result = $this->first();
        if (!empty($result)) {
            return array_values($result);
        }
        return [];
    }

    public function getRowCount(): int
    {
        return $this->executeQuery()->rowCount();
    }
}