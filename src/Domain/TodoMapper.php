<?php

namespace Domain;

use Doctrine\DBAL\Connection;

class TodoMapper
{
    private $database;

    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    /**
     * Returns the number of all todos.
     *
     * @return int
     */
    public function countAll()
    {
        return (int) $this->database->fetchColumn('SELECT COUNT(*) FROM todo');
    }

    public function findAll()
    {
        return $this->database->fetchAll('SELECT * FROM todo');
    }

    public function find($id)
    {
        return $this->database->fetchAssoc('SELECT * FROM todo WHERE id = ?', [ $id ]);
    }

    /**
     * Creates a new todo in the database.
     *
     * @param  string $title
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function create($title)
    {
        if (empty($title)) {
            throw new \InvalidArgumentException('Missing title to create a new todo.');
        }

        if (!$this->database->insert('todo', [ 'title' => $title ])) {
            throw new \RuntimeException('Unable to create new todo.');
        }

        return (int) $this->database->lastInsertId();
    }

    public function close($id)
    {
        return $this->database->update('todo', [ 'is_done' => 1], [ 'id' => $id ]);
    }

    public function delete($id)
    {
        return $this->database->delete('todo', [ 'id' => $id ]);
    }
} 
