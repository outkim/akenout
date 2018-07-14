<?php

require_once '../vendor/autoload.php';

//use GuzzleHttp\Client;

$clientBuilder = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://84.211.194.175/');
$client = $clientBuilder->buildAuthenticatedByPassword('1_1pfu7sn7doqscw4kksow8sgco8ko0kk00cks8w4wkccko8ggg4', '299q4wk2zxxcs4cg40wg0o4ocs4044s4ows4w40008kck88g0o', 'kimbm',
    'ikkeSikker123');

$product = $client->getProductApi()->get('9780007318865');
echo "ID: " . $product['identifier'] . "<br>";
echo "Family: " . $product['family'];

$searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
$searchBuilder
    ->addFilter('completeness', '>', 70, ['scope' => 'ecommerce']);

$searchFilters = $searchBuilder->getFilters();

$firstpage = $client->getProductApi()->all(50, ['search' => $searchFilters, 'scope' => 'ecommerce']);

foreach($firstpage as $product){
    echo "<pre>";
    echo $product['identifier'];
    echo "</pre>";
}








