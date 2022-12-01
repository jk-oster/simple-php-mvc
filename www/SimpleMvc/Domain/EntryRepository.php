<?php

namespace SimpleMvc\Domain;

use SimpleMvc\Framework\BaseRepository;

class EntryRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct("entry", "MyMicroBlogEntry");
    }

    /**
     * Gets all entries
     * @return array of all entries
     */
    public function getEntriesSorted(): array
    {
        $sql = "SELECT * FROM $this->tableName ORDER BY highlight DESC, edited DESC;";
        return $this->selectAll($sql);
    }

    public function toggleHighlight(string $entryId): void
    {
        $entry = $this->selectByPk($entryId);
        $highlight = ((int) $entry->highlight) === 1 ? 0 : 1;
        $data = ['highlight' => $highlight];
        $this->update($entryId, $data);
    }
}