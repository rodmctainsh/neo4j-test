<?php

namespace Tests;

use App\Choice;
use Laudis\Neo4j\Client;
use Laudis\Neo4j\ClientBuilder;
use PHPUnit\Framework\TestCase;

class ChoiceTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = ClientBuilder::create()
            ->addHttpConnection('backup', 'http://neo4j:neo4j@localhost:7687')
            ->addBoltConnection('default', 'bolt://neo4j:neo4j@localhost:7687')
            ->setDefaultConnection('default')
            ->build();        
    }

    /** @test */
    public function it_returns_the_items_others_have_also_chosen()
    {
        $choice = new Choice('Bucket', $this->client);

        $this->assertEquals(['Other Bucket'], $choice->alsoChosen());
    }

    /** @test */
    public function it_returns_the_items_that_are_used_with_the_chosen_one()
    {
        $choice = new Choice('Bucket', $this->client);

        $this->assertEquals(['Spade'], $choice->alsoUsed());
    }
}