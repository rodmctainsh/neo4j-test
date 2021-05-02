<?php

namespace App;

use Laudis\Neo4j\Client;
use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\HttpDriver\Transaction;

class GraphDB
{
    private Client $client;

    private ?Transaction $transaction = null;

    public function __construct($connectionString)
    {
        $this->client = ClientBuilder::create()
            ->addHttpConnection('backup', "http://{$connectionString}")
            ->addBoltConnection('default', "bolt://{$connectionString}")
            ->setDefaultConnection('default')
            ->build();
    }

    public function transaction()
    {
        $this->transaction = $this->client->openTransaction();

        return $this;
    }

    public function rollback()
    {
        $this->transaction->rollback();

        $this->transaction = null;
    }

    public function run($query, $parameters = [])
    {
        return collect(
            $this->transaction
                ? $this->transaction->run($query, $parameters)->toArray()
                : $this->client->run($query, $parameters)->toArray()
        );
    }
}