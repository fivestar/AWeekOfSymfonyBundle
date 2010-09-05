<?php

namespace Bundle\AWeekOfSymfonyBundle\Repository;

use Bundle\AWeekOfSymfonyBundle\Model\Entry;

/**
 * Entry repository (using pdo, sqlite3)
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 */
class EntryRepository
{
    protected $file;
    protected $db;

    public function __construct($file)
    {
        $this->file = $file;
        $this->initDb();
    }

    public function has($path)
    {
        $sql = "SELECT count(path) FROM entry WHERE path = :path";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':path' => $path));

        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    public function get($path)
    {
        $sql = "SELECT data FROM entry WHERE path = :path";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':path' => $path));

        $data = $stmt->fetchColumn();
        if (!$data) {
            return false;
        }

        $entry = unserialize($data);

        return $entry;
    }

    public function store(Entry $entry)
    {
        $path = $entry->getPath();

        $now = new \DateTime();
        $params = array(
            ':path' => $path,
            ':data' => serialize($entry),
            ':updated_at' => $now->format('Y-m-d H:i:s'),
        );

        if ($this->has($path)) {
            $sql = "UPDATE entry SET data = :data, updated_at = :updated_at WHERE path = :path";
        } else {
            $sql = "INSERT INTO entry VALUES(:path, :data, :created_at, :updated_at)";
            $params[':created_at'] = $now->format('Y-m-d H:i:s');
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    public function remove($path)
    {
        $sql = "DELETE FROM entry WHERE path = :path";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':path' => $path));

        return $stmt->rowCount();
    }

    protected function initDb()
    {
        if (!isset($this->db)) {
            $db = new \PDO('sqlite:' . $this->file);

            $db->exec('CREATE TABLE IF NOT EXISTS entry (path STRING, data STRING, created_at DATETIME, updated_at DATETIME)');
            $db->exec('CREATE UNIQUE INDEX IF NOT EXISTS entry_token ON entry (path)');

            $this->db = $db;
        }

        return $this->db;
    }

    public function __destruct()
    {
        if (isset($this->db)) {
            unset($this->db);
        }
    }
}
