<?php

namespace App\Models;

use SimpleMvc\BaseRepository;

class EntryRepository extends BaseRepository
{
    public static function getInstance($tableName = '', $domainName = ""): static
    {
        if (self::$instance === null) {
            self::$instance = new self($tableName, $domainName);
        }
        return self::$instance;
    }

}