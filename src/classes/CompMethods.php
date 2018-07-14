<?php

require_once ('MagentoConnector.php');
require_once ('AkeneoConnector.php');
require_once ('../include.php');

class CompMethods
{
    //This function will make a check between the categories in Akeneo and the categories in Magento, to see if they are the same.
    //This is important, because we do not wish to push products into a category that does not exist, and we also want to make sure that
    //all category creation and management is being handled by Akeneo. Therefore we can't have categories in Magento that does not exist in Akeneo.

    public static function categories_check(){

        //Open a new connection to each client.
        $mag_client = new MagentoConnector();
        $ak_client = new AkeneoConnector();

        //These are being declared now, and will hold any deltas between the lists.
        $missing_mag;
        $missing_ak;


        //Get each of the category lists.
        $categories_ak = $ak_client->get_categories();
        $categories_mag = $mag_client->get_categories();

        foreach($categories_mag as $k => $v){
             if(!isset($categories_ak[$k])){
                 $missing_ak[$k] = $v;
             }
        }

        foreach($categories_ak as $k => $v){
            if(!isset($categories_mag[$k])){

                $missing_mag[$k] = $v;
            }

        }

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
