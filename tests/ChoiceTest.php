<?php

namespace Tests;

use App\Choice;
use App\GraphDB;
use PHPUnit\Framework\TestCase;

class ChoiceTest extends TestCase
{
    private GraphDB $db;

    protected function setUp(): void
    {
        parent::setUp();

        $this->db = (new GraphDB('neo4j:neo4j@localhost:7687'))
            ->transaction();
    }

    protected function tearDown(): void
    {
        $this->db->rollback();

        parent::setUp();
    }

    /** @test */
    public function it_returns_the_items_others_have_also_requested_for_a_given_choice()
    {
        $this->db->run("CREATE (:Item {name: 'Bucket'})-[:REQUESTED_WITH]->(:Item {name: 'Other Bucket'})");
        $this->db->run("
            MATCH (bucket:Item {name: 'Bucket'})
            CREATE (bucket)-[:REQUESTED_WITH]->(:Item {name: 'Spade'})
        ");

        $choice = new Choice('Bucket', $this->db);

        $this->assertEquals(['Other Bucket', 'Spade'], $choice->requestedWith());
    }

    /** @test */
    public function it_returns_the_items_that_are_used_with_the_chosen_item()
    {
        $this->db->run("CREATE (:Item {name: 'Bucket'})-[:USED_WITH]->(:Item {name: 'Spade'})");
        $this->db->run("
            MATCH (bucket:Item {name: 'Bucket'})
            CREATE (bucket)-[:USED_WITH]->(:Item {name: 'Gloves'})
        ");

        $choice = new Choice('Bucket', $this->db);

        $this->assertEquals(['Spade', 'Gloves'], $choice->usedWith());
    }
}