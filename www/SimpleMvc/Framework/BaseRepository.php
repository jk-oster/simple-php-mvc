<?php

namespace SimpleMvc\Framework;

use mysqli_result;

/**
 * Base Model Class for domain repositories.
 * Automatically retrieves all fields & keys of domain in database
 * by naming convention of extended Model
 * (e.q. 'UserRepository' -> DomainClassName = 'User', DataBaseTableName = 'user').
 * Provides methods to:
 * - CREATE new rows in DomainTable
 * - RETRIEVE rows from DomainTable
 * - UPDATE rows of DomainTable
 * - DELETE rows from DomainTable
 *
 * @author Jakob Osterberger
 * @date 10.06.2022
 */
abstract class BaseRepository extends Database
{
    protected string $tableName;
    protected array $fields;
    protected string $domainName;

    public function __construct($tableName = '', $domainName = "")
    {
        // Set Domain name matching model
        $this->domainName = $domainName !== '' ? $domainName : $this->getDomainName();
        // Set name of database table which is storage of model data
        $this->tableName = $tableName !== '' ? $tableName : strtolower($this->domainName);

        // Get all fields of DataBase table
        $sql = "SELECT * FROM $this->tableName;";
        $this->fields = $this->getFieldNames($sql);
        // Get primary key field of table
        $this->fields['pk'] = $this->getPrimaryKey($this->tableName);
        $this->fields['fk'] = $this->getForeignKeys($this->tableName);
    }

    protected function getRootNamespace(): string
    {
        return explode('\\', get_class($this))[0];
    }

    protected function getDomainClassName(): string
    {
        return $this->getRootNamespace() . "\\Domain\\Domain\\" . $this->domainName;
    }

    /**
     * Gets Domain name from class name e.q. SimpleMvc\Domain\UserRepository -> 'User'
     * @return string
     */
    protected function getDomainName(): string
    {
        $nameArr = explode('\\', get_class($this));
        return str_replace(['Model', 'Repository'], '', end($nameArr));
    }

    protected function mapToDomain($array): mixed
    {
        $className = $this->getDomainClassName();
        return $className::objectFrom($array);
    }

    protected function mapAllToDomain($array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = $this->mapToDomain($value);
        }
        return $result;
    }

    /**
     * Get primary field
     * @return mixed
     */
    protected function getPrimaryKey($table): mixed
    {
        $sql = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY';";
        return $this->getRow($sql)['Column_name'];
    }

    protected function getForeignKeys($tableName): array
    {
        $sql = "select TABLE_NAME from INFORMATION_SCHEMA.TABLE_CONSTRAINTS where TABLE_NAME='"
            . $tableName . "' and TABLE_SCHEMA='database' and CONSTRAINT_TYPE = 'FOREIGN KEY';";
        $tables = $this->getCol($sql);
        $result = [];
        foreach ($tables as $table) {
            $result[] = [$table, $this->getPrimaryKey($table)];
        }
        return $result;
    }

    /**
     * Get number of database entries
     * @return int|string|false
     */
    public function total(): int|string|false
    {
        $sql = "SELECT count(*) FROM {$this->tableName};";
        return $this->getOne($sql);
    }

    /**
     * Select entry by primary key
     * @param $pk
     * @return array|false
     */
    public function selectByPk($pk): mixed
    {
        $sql = "SELECT * FROM $this->tableName WHERE {$this->fields['pk']}='$pk';";
        return $this->mapToDomain($this->getRow($sql));
    }

    public function selectAll($sql = ''): mixed
    {
        if ($sql === '') {
            $sql = "SELECT * FROM $this->tableName";
        }
        return $this->mapAllToDomain($this->getAll($sql));
    }

    /**
     * Select entries where database field matches value
     * @param string $field
     * @param mixed $value
     * @return array|false
     */
    public function selectBy(string $field, mixed $value): mixed
    {
        if (in_array($field, $this->fields, true)) {
            $sql = "SELECT * FROM $this->tableName WHERE $field = '$value';";
            return $this->mapAllToDomain($this->getAll($sql));
        }
        echo "Error: Cannot select unknown field: '$field'";
        return false;
    }

    /**
     * Inserts a row into database
     * @param array $data
     * @return bool|mysqli_result
     */
    public function insert(mixed $data): bool|mysqli_result
    {
        if(is_object($data)){
            $data = $this->getDomainClassName()::toArray($data);
        }
        $DB = $this->openDB();
        $inFields = [];
        $inValues = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fields, true)) {
                $inFields[] = $key;
                $inValues[] = "'{$DB->real_escape_string($value)}'";
            } else {
                echo "Error: Cannot insert unknown field: '$key'";
                return false;
            }
        }
        $dataFields = implode(', ', $inFields);
        $valueFields = implode(', ', $inValues);
        $sql = "INSERT INTO $this->tableName ($dataFields) VALUES ($valueFields);";
        $result = $DB->query($sql);
        $this->closeDB();
        return $result;
    }

    /**
     * Updates column matching key
     * @param $pKey
     * @param array $data
     * @return bool|mysqli_result
     */
    public function update($pKey, mixed $data): bool|mysqli_result
    {
        if ($this->selectByPk($pKey)) {
            if(is_object($data)){
                $data = $this->getDomainClassName()::toArray($data);
            }
            $DB = $this->openDB();
            $upList = [];
            foreach ($data as $key => $value) {
                if (in_array($key, $this->fields, true)) {
                    $upList[] = "$key='{$DB->real_escape_string($value)}'";
                } else {
                    echo "Error: Cannot update unknown field: '$key'";
                    return false;
                }
            }
            $setStr = implode(', ', $upList); // e.q. "name='Rudi', text='New Text here'"
            $sql = "UPDATE $this->tableName SET $setStr WHERE {$this->fields['pk']}='$pKey';";
            $result = $DB->query($sql);
            $this->closeDB();
            return $result;
        }
        echo "Error: Cannot update fields of nonexistent field: '$pKey'";
        return false;
    }

    /**
     * Deletes database row matching key
     * @param $key
     * @return bool|mysqli_result
     */
    public function delete($key): bool|mysqli_result
    {
        $sql = "DELETE FROM $this->tableName WHERE {$this->fields['pk']}=$key;";
        return $this->executeQuery($sql);
    }
}
