<?php

namespace MyMicroBlog\Framework;

use mysqli;
use mysqli_result;

/**
 * Database class to ease DB access, provides methods to:
 * - establish / close DataBase connection
 * - execute SQL queries
 * - retrieve all results
 * - retrieve first row of results
 * - retrieve first column of results
 * - retrieve first value of results
 *
 * @author Jakob Osterberger
 * @date 10.06.2022
 */
class DataBase
{
    protected mysqli $DB;

    // open mysql data base
    protected function openDB(): mysqli
    {
        $this->DB = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if ($this->DB->connect_error) {
            die ("Could not establish database connection: " . $this->DB->connect_error);
        }
        return $this->DB;
    }

    // close database
    protected function closeDB(): bool
    {
        if (!$this->DB->close()) {
            echo "Warning: Cold not close db connection!<br/>";
            return false;
        }
        return true;
    }

    public function executeQuery(string $queryString): mysqli_result|bool
    {
        $conDB = $this->openDB();
        // echo "<br>$queryString<br>";
        $result = $conDB->query($queryString);
        if(!$result) {
            die($conDB->errno.':'.$conDB->error.'<br />Error SQL statement is '.$queryString.'<br />');
        }
        $this->closeDB();
        return $result;
    }

    public function getRow($sql): array|false
    {
        if ($result = $this->executeQuery($sql)) {
            return $result->fetch_assoc() ?? false;
        }
        return false;
    }

    public function getFieldNames($sql): array|false
    {
        if ($result = $this->executeQuery($sql)) {
            $list = [];
            while($res = $result->fetch_field()){
                $list[] = $res->name;
            }
            return $list;
        }
        return false;
    }

    public function getAll($sql, $mode=MYSQLI_ASSOC): array|bool
    {
        if ($result = $this->executeQuery($sql)) {
            return $result->fetch_all($mode);
        }
        return false;
    }

    public function getCol($sql): array|false
    {
        if ($result = $this->executeQuery($sql)) {
            $list = [];
            while ($row = $result->fetch_row()) {
                $list[] = $row[0];
            }
            return $list;
        }
        return false;
    }

    public function getOne($sql): mixed
    {
        if ($result = $this->executeQuery($sql)) {
            $row = $result->fetch_row();
            if ($row) {
                return $row[0];
            }
        }
        return false;
    }
}