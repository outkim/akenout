<?php
/**
 * Created by PhpStorm.
 * User: kimba
 * Date: 12/07/2018
 * Time: 16:50
 */

class Product
{
    //This might/should be sent inn as an array, and might not need its own variables. This is more to keep track of all the variables that are needed.
    //What variables are available, is in theory dynamic depending on the attributes set up in the PIM, so one might want to set up something more dynamic than this.
    protected $sku;
    protected $name;
    protected $attribute_set_id;
    protected $price;
    protected $status;
    protected $visibility;
    protected $type_id;
    protected $weight;
    protected $barcode;
    protected $distributor_id;
    protected $author;
    protected $series;
    protected $publisher;
    protected $genre;
    protected $dimentions;
    protected $pages;

    //format and language have an option value, on a Hardcover it's shown as "14", this needs to be correlated between Magento and PIM.
    protected $format_books;
    protected $language;

    //The following section are all arrays.

    //I think there can be multiple website and category id's to one product, this has to be considered before he code is final.
    //The website ids and category links are sent within an array called extension_attributes, not by themselves.

    //Postion only seems to relate to the list on the Magento product page. Due to our old category structure, all products only have 1 category, so for us there should only 1 category at position 0

    protected $category_links = ['position' => '', 'category_id'=>''];
    protected $website_ids = [];
    protected $extension_attributes = ['website_ids' => $this->website_ids, 'category_links' => $this->category_links];

    protected $product_links = [];
    protected $options = [];

    //Not sure how to handle media, so even though this field is declared, and I will leave it as such just to help me remember it, it is currently not in use.
    protected $media_gallery_entries = [];

    //Not sure how to handle these yet, custom attributes are custom, not sure at this moment how I will figure out what attribute belongs to a product dynamically.
    protected $product_description;

    //This is returned in the return statement, might fill this field while also populating media, the image location is in magento2/pub/media/catalog/folder/folder/image_name.jpg
    //It is returned with the custom attributes JSON array which is declared in the __constructor.
    protected $image_location;

    public function __construct()
    {
        protected $custom_attributes = [
            ['attribute code' => 'description', 'value' => $this->product_description],
        ['attribute_code' => 'category_ids', 'value' => []],
        ['attribute_code' => 'options_container', 'value' => 'container2'],
        ['attribute_code' => 'required_options', 'value' => 0],
        ['attribute_code' => 'has_options', 'value' => 0],
        ['attribute_code' => 'url_key', 'value' => $this->sku],
        ['attribute_code' => 'gift_message_available', 'value' => 0],
        ['attribute_code' => 'tax_class_id', 'value' => 0],
        ['attribute_code' => 'barcode', 'value' => $this->barcode ],
        ['attribute_code' => 'distributor_sku', 'value' => $this->distributor_id ],
        ['attribute_code' => 'title', 'value' => $this->name ],
        ['attribute_code' => 'series', 'value' => $this->series ],
        ['attribute_code' => 'author', 'value' => $this->author ],
        ['attribute_code' => 'format_books', 'value' => $this->format_books ],
        ['attribute_code' => 'publisher', 'value' => $this->publisher ],
        ['attribute_code' => 'genre', 'value' => $this->genre ],
        ['attribute_code' => 'language', 'value' => $this->language ],
        ['attribute_code' => 'dimentions', 'value' => $this->dimentions ],
        ['attribute_code' => 'pages', 'value' => $this->pages ],
        ['attribute_code' => 'publishing_year', 'value' => $this->publishing_year ]];

    }




   // , ];





}