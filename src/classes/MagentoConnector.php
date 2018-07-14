<?php
require('../include.php');

//For this project, I am using GuzzleHTTP to send and recieve json requests from Mangeto.
use GuzzleHttp\Client;

class MagentoConnector
{


    protected $client_magento;
    protected $products_uri;
    protected static $headers;
    protected static $base_uri;
    protected $categories_uri;
    public static $categories = [];

    //protected $res;


    public function __construct($args = [])
    {
        //I have my current test setup values in place as default, but these lines of code allows the connector to be used with other sites or with new authentication token.

        $this->products_uri = $args['products_uri'] ?? '/rest/V1/products/';
        $this->categories_uri = $args['categories_uri'] ?? '/rest/V1/categories';

        self::$base_uri = $args['base_uri'] ?? 'http://outland.sytes.net/index.php';

        //The authorization part of the HTTP Header needs a 'Bearer ' in front of the code.
        if(isset($args['authorization'])){
            if(strpos($args['authorization'], 'Bearer ') !== 0){
                $authorization = 'Bearer ' . $args['authorization'];
            }else{$authorization = $args['authorization'];
            }
        }else{
            $authorization = 'Bearer isrt6f97v11dxb8axmxqqrrxdc8yhh08';
        }
        //Sets the header so we dont have to type it for every request.
        self::$headers = ['Authorization' => $authorization, 'Accept' => 'application/json', 'Content-type' => 'application/json'];

        //Instantiates a new Guzzle Client class.
        try {
            $this->client_magento = new Client(['base_uri' => static::$base_uri]);

        } catch (GuzzleHttp\Exception\ClientException $e) {
            echo $e->getMessage();
        }

    }

    //This function retrieves productinformation as an array based on the sku aka product indentifier.
    public function get_product_by_id($product_id_array = []){
        foreach($product_id_array as $product_id) {
            try {
                $product_id_uri = $this->products_uri . $product_id;
                $res = $this->client_magento->request('GET', $product_id_uri, ['headers' => static::$headers]);

            } catch (GuzzleHttp\Exception\GuzzleException $e) {
                echo $e->getMessage();
            }
            if (isset($res)) {
                //HTTP request is sent as json, as mentioned in the header, and has to be decoded as such.
                $body2 = $res->getBody()->getContents();
                $body = json_decode($body2);
                echo "<pre>";
                //print_r($body);
                echo "</pre>";
                echo "<pre>";
                print_r($body2);
                echo "</pre>";

                echo "<hr>";
            }
        }
    }


    //this function may appear unused, but it's used ny the array_walk_recursive() function in get_categories, to itterate through each nested array and get the id and name values.
    private static function find_category_names($value, $key)
    {
        global $id;
        global $name;
        global $categories;
//        echo "test 2: ";
//        print_r($categories);
//        echo "<br>";


        if ($key == 'id') {
            $id = intval($value);
        } elseif ($key == 'name') {
            $name = strval($value);
        }

        if (is_string($name) && is_int($id)) {
//                echo $name . " is " . $id;
            $categories[$name] = $id;
            $id = null;
            $name = null;
        }
        self::$categories = $categories;
    }


    public function get_categories()
    {
        //GET request for the categories.
        try {
            $res = $this->client_magento->request('GET', $this->categories_uri, ['headers' => static::$headers]);
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            echo $e->getMessage();
        }

        //Decode the JSON file to an array.
        $body = json_decode($res->getBody()->getContents(), true);


        //There are alot of brancing and nested arrays depending on how the categories are set up in Magento/Akeneo, however we are only interested in the name and id values of each array,
        //regardless of nesting.
        //This function will go throuch each nested array recursively and do the function named in the second argument, which is the find_category_name() function above.
        array_walk_recursive($body, 'self::find_category_names');

        return self::$categories;
    }


    //The main use of this function would be to take an array of products, iterate through them, and place them in the magento catalog. Each product needs to be an array that includes
    // information about title and so forth.
    //The theory here is that the products will be fetched from Akeno PIM.
    public function add_product($array_with_product_arrays = []){
        foreach($array_with_product_arrays as $product_array){
            print_r($product_array);
        }

    }


}
