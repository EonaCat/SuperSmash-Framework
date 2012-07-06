<?php

class ChooserModel extends system\SuperSmash\Model 
{

	// Create the constructor
    public function __construct() 
    {
       parent::__construct();
    }

   public function chooser() 
   {
        return array('chooserMessage' => 
                        'This portal contains several websites <br />
                         Please click on the website that you would like to visit <br /><br />
                    ');
   }

   public function denyList() 
   {
        return array('denyList' => 
                      '.htaccess');
   }   
}
?>