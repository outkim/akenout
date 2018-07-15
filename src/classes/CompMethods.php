<?php

require_once ('MagentoConnector.php');
require_once ('AkeneoConnector.php');
require_once ('../include.php');

class CompMethods
{
    //This function will make a check between the categories in Akeneo and the categories in Magento, to see if they are the same.
    //This is important, because we do not wish to push products into a category that does not exist, and we also want to make sure that
    //all category creation and management is being handled by Akeneo. Therefore we can't have categories in Magento that does not exist in Akeneo.

    protected static function add_category_magento($categories_combined_array){

        //Here we have to check if categories marked as missing also have any parents that might be missing, so we do not try to add category children without parents.
        //In theory, the parents should always come before the children, I THINK, but I want to be sure that we can handle an exception where a child might come first in the loop.
        $i = 0;
        do{
            foreach ($categories_combined_array as $cat_name => $cat_ids){
                //Check if there are any missing categories in array.
                if($cat_ids['mag_id'] == 'missing!'){
                    echo $cat_name . " is missing.<br>";
                    $missing = true;

                    $ak_parent_id = $cat_ids['ak_parent_id'];
                    //Check that parent exists in the array
                    foreach($categories_combined_array as $cat_name2 => $cat_ids2){
                        if ($cat_ids2['ak_id'] == $ak_parent_id){
//                            echo $cat_name2 . " is parent to missing category <br>";

                            //Checks that the parent exists in magento and get the magento id and add it to the $categories_combined_array.
                            if($cat_ids2['mag_id'] != 'missing!'){
                                $categories_combined_array[$cat_name]['mag_parent_id'] = $cat_ids2['mag_id'];
                                print_r($categories_combined_array[$cat_name]) ;

                                $mag_cat_update_client = new MagentoConnector();
                                $mag_cat_update_client->add_category($categories_combined_array[$cat_name]);
                                unset($mag_cat_update_client);


                                echo "parent exists. <br>";
                            }

                        }
                    }


                }



            }
            $i++;
            $missing = false;
        }while($missing == true && $i < 20);
//
    }

    public static function categories_check(){

        //Open a new connection to each client.
        $mag_client = new MagentoConnector();
        $ak_client = new AkeneoConnector();

        //These are being declared now, and will hold any deltas between the lists.
        $missing_mag = false;
        $missing_ak;


        //Get each of the category lists.
        $categories_ak = $ak_client->get_categories();
        $categories_mag = $mag_client->get_categories();

        $categories_comb = [];

        foreach($categories_ak as $ak_category_name => $ak_ids){

            if(isset($categories_mag[$ak_category_name])){
                $mag_id = $categories_mag[$ak_category_name]['id'];
                $mag_parent_id = $categories_mag[$ak_category_name]['parent_id'];
            }else{
                $mag_id = 'missing!';
                $mag_parent_id = 'missing!';
                $missing_mag = true;
            }
            $categories_comb [$ak_category_name] = ['ak_id' => $ak_ids['id'], 'ak_parent_id' => $ak_ids['parent_id'], 'mag_id' => $mag_id, 'mag_parent_id' => $mag_parent_id];

        }

        if($missing_mag){
            self::add_category_magento($categories_comb);
        }


        print_r($categories_comb);


//        if(array_count_values($categories_ak) != array_count_values($categories_mag)){
//
//        }



//        foreach($categories_mag as $k => $v){
//             if(!isset($categories_ak[$k])){
//                 $missing_ak[$k] = $v;
//             }
//        }
//
//        foreach($categories_ak as $k => $v){
//            if(!isset($categories_mag[$k])){
//
//                $missing_mag[$k] = $v;
//            }
//
//        }

        //Check if there were any missing categories.
        if(isset($missing_mag) || isset($missing_ak)){

            if(isset($missing_mag)){
                $comp_check_fail = ['missing_mag' => $missing_mag];
            }
            if(isset($missing_ak)){
                $comp_check_fail = ['missing_ak' => $missing_ak];
            }
            //Return an array with missing categories and from which list.
            echo "<br> There were missing categories! <br>";
            return $comp_check_fail;

        }else{

            //Return true if all is ok.
            return true;
        }



    }


}
echo "<pre>";
print_r(CompMethods::categories_check());


echo "</pre>";
