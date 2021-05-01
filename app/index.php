<?php

namespace App;

use Laudis\Neo4j\ClientBuilder;

require 'vendor/autoload.php';

$client = ClientBuilder::create()
    ->addHttpConnection('backup', 'http://neo4j:neo4j@localhost:7687')
    ->addBoltConnection('default', 'bolt://neo4j:neo4j@localhost:7687')
    ->setDefaultConnection('default')
    ->build();

$choice = new Choice($argv[1] ?? '', $client);

$alsoChosen = $choice->alsoChosen();

if (count($alsoChosen)) {
    echo sprintf('People have also chosen: %s'.PHP_EOL, implode(', ', $alsoChosen));
}

$alsoUsed = $choice->alsoUsed();

if ($alsoUsed) {
    echo sprintf('Are you also looking for one of these? %s'.PHP_EOL, implode(', ', $alsoUsed));
}
