<?php
class AkeneoConnector
{

    protected $client;
    protected $base_uri;

    //When consructing a new object, we will open a new Akeneo client connection, using either provided values or default values.
    public function __construct($args = [])
    {
        $this->base_uri = $args['base_uri'] ?? 'http://84.211.194.175/';
        $client_id = $args['client_id'] ?? '1_1pfu7sn7doqscw4kksow8sgco8ko0kk00cks8w4wkccko8ggg4';
        $secret = $args['secret'] ?? '299q4wk2zxxcs4cg40wg0o4ocs4044s4ows4w40008kck88g0o';
        $user = $args['user'] ?? 'kimbm';
        $password = $args['password'] ?? 'ikkeSikker123';

        $clientbuilder = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder($this->base_uri);
        $this->client = $clientbuilder->buildAuthenticatedByPassword($client_id, $secret, $user, $password);

    }

    public function get_product_by_id($product_id_array = [])
    {
        $product = [];
        foreach ($product_id_array as $product_id) {
            $product = [$this->client->getProductApi()->get($product_id)];


        }

    return $product;
    }

    //Fetch all the category names from Magento. In the future, we might want to change this to also include the Akeneo Code for return.
    public function get_categories(){
        //Open connection to the Akeneo Category API
        $categories = $this->client->getCategoryApi()->all(100);

        //This array will be used to store labels during the foreach loop.
        $categories_array = [];
        foreach($categories as $category){
            //Currently we only need the Norwegian label to check if it exists within the Magento database.
            $categories_array[$category['labels']['nb_NO']] = $category['code'];

        }

        return $categories_array;

    }

    public function get_products_by_search(){
        $searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
        $searchBuilder->addFilter('completeness', '>', 70, ['scope' => 'ecommerce']);

        $searchFilters = $searchBuilder->getFilters();

        $products = $this->client->getProductApi()->all(50, ['search' => $searchFilters, 'scope' => 'ecommerce']);

        return $products;

    }


}
