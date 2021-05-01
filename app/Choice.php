<?php

namespace App;

use Laudis\Neo4j\Client;

class Choice
{
    private $name;

    private Client $client;

    public function __construct($name, Client $client)
    {
        $this->name = $name;
        $this->client = $client;
    }

    public function alsoChosen()
    {
        return $alsoChosen = $this->client->run(
            'MATCH (:Item {name: $name})-[:REQUESTED_WITH]-(i:Item) RETURN i',
            ['name' => $this->name]
        )
            ->map(fn ($item) => $item->get('i')['name'])
            ->toArray();
    }

    public function alsoUsed()
    {
        return $this->client->run(
            'MATCH (:Item {name: $name})-[:USED_WITH]-(i:Item) RETURN i',
            ['name' => $this->name]
        )
            ->map(fn ($item) => $item->get('i')['name'])
            ->filter(fn ($itemName) => array_search($itemName, $this->alsoChosen()) === false)
            ->toArray();
    }
}