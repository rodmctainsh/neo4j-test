<?php

namespace App;

use App\GraphDB;

class Choice
{
    private $name;

    private GraphDB $db;

    public function __construct($name, GraphDB $db)
    {
        $this->name = $name;
        $this->db = $db;
    }

    public function requestedWith()
    {
        return $this->db->run(
            'MATCH (:Item {name: $name})-[:REQUESTED_WITH]-(i:Item) RETURN i',
            ['name' => $this->name]
        )
            ->map(fn ($item) => $item['i']['name'])
            ->sort()
            ->values()
            ->all();
    }

    public function usedWith()
    {
        return $this->db->run(
            'MATCH (:Item {name: $name})-[:USED_WITH]-(i:Item) RETURN i',
            ['name' => $this->name]
        )
            ->map(fn ($item) => $item['i']['name'])
            ->sort()
            ->values()
            ->all();
    }
}