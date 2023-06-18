<?php

namespace SimpleMvc;

/**
 * Class BaseRepository
 *
 * This class represents a base repository in the application to be used in controllers.
 * It is to get data from database and return it as a domain object.
 * It also can be used to save, update and delete data from database using domain object (CRUD).
 *
 * @example
 * $userRepository = \App\UserRepository::getInstance();
 * $userRepository->findAll(); // returns all users
 * $userRepository->find(1); // returns user with id 1
 * $userRepository->findBy('name', 'John'); // returns user with name 'John'
 * $userRepository->findBy('name', '%John%', 'LIKE'); // returns user with name 'John' using LIKE operator
 */
abstract class BaseRepository
{
    protected static $instance;
    protected string $tableName;
    protected array $fields;
    protected string $domainName;

    public QueryBuilder $queryBuilder;

    abstract public static function getInstance(): static;

    public function __construct($tableName = '', $domainName = '')
    {
        // Set Domain name matching model
        $this->domainName = $this->getDomainName($domainName);
        // Set name of database table which is storage of model data
        $this->tableName = $this->getTableName($tableName);

        $this->queryBuilder = new QueryBuilder($this->tableName);

        $this->setFields();
    }

    private function setFields(): void
    {
        // Get all fields of DataBase table
        $this->fields = $this->queryBuilder->select()->getFieldNames();
        // Get primary key field of table
        $this->fields['pk'] = $this->getPrimaryKey($this->tableName);
        $this->fields['fk'] = $this->getForeignKeys($this->tableName);
    }

    protected function getRootNamespace(): string
    {
        return explode('\\', get_class($this))[0];
    }

    protected function getDomainName($domainName = ''): string
    {
        if ($domainName !== '') {
            return $domainName;
        }
        $nameArr = explode('\\', get_class($this));
        return str_replace(['Models', 'Repository'], '', end($nameArr));
    }

    protected function getTableName($tableName = ''): string
    {
        if ($tableName !== '') {
            return $tableName;
        }
        return strtolower($this->domainName);
    }

    protected function getDomainClassName(): string
    {
        return $this->getRootNamespace() . "\\Models\\Domain\\" . $this->domainName;
    }

    /**
     * Get primary field
     * @return mixed
     */
    protected function getPrimaryKey($table): mixed
    {
        $sql = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY';";
        return $this->queryBuilder->setQuery($sql)->first()['Column_name'];
    }

    protected function getForeignKeys($tableName): array
    {
        $sql = "select TABLE_NAME from INFORMATION_SCHEMA.TABLE_CONSTRAINTS where TABLE_NAME='"
            . $tableName . "' and TABLE_SCHEMA='" . DB_NAME
            . "' and CONSTRAINT_TYPE = 'FOREIGN KEY';";
        $tables = $this->queryBuilder->setQuery($sql)->getCol();
        $result = [];
        foreach ($tables as $table) {
            $result[] = [$table, $this->getPrimaryKey($table)];
        }
        return $result;
    }

    // ==========================================================
    // MAPPER
    // ==========================================================

    protected function mapRowToDomain($array): object
    {
        $className = $this->getDomainClassName();
        return $className::objectFrom($array);
    }

    protected function mapDomainToArray(object $object): array
    {
        $className = $this->getDomainClassName();
        return $className::toArray($object);
    }

    protected function mapRowsToDomain($array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = $this->mapRowToDomain($value);
        }
        return $result;
    }

    protected function mapRowsToDomainCollection($array): Collection
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = $this->mapRowToDomain($value);
        }
        return new Collection($result);
    }


    // ==========================================================
    // CRUD
    // ==========================================================

    public function find(int $id): ?object
    {
        $row = $this->queryBuilder->select()->where($this->fields['pk'], '=', $id)->first();
        if (!$row) {
            return null;
        }
        return $this->mapRowToDomain($row);
    }

    public function findBy(string $field, mixed $value, $operator = '='): ?object
    {
        $row = $this->queryBuilder->select()->where($field, $operator, $value)->first();
        if (!$row) {
            return null;
        }
        return $this->mapRowToDomain($row);
    }

    public function findAll(): array
    {
        $rows = $this->queryBuilder->select()->get();
        if (!$rows) {
            return [];
        }
        return $this->mapRowsToDomain($rows);
    }

    public function where(string $field, mixed $value): array
    {
        $rows = $this->queryBuilder->select()->where($field, '=', $value)->get();
        if (!$rows) {
            return [];
        }
        return $this->mapRowsToDomain($rows);
    }

    public function delete($id): bool
    {
        return $this->queryBuilder->delete()->where($this->fields['pk'], '=', $id)->getRowCount();
    }

    public function save(object|array $data): int
    {
        if (is_object($data)) {
            $data = $this->mapDomainToArray($data);
        }

        foreach ($data as $key => $value) {
            if (!in_array($key, $this->fields, true)) {
                throw new \RuntimeException("Error: Cannot update or insert into unknown field: '$key'");
            }
        }

        $pKey = $data[$this->fields['pk']];
        $entity = $this->find($pKey);

        if ($entity) {
            return $this->queryBuilder->update($data)->where($this->fields['pk'], '=', $pKey)->getRowCount();
        }
        return $this->queryBuilder->insert($data);
    }

}