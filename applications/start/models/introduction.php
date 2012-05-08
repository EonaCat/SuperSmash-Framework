<?php

class IntroductionModel extends System\SuperSmash\Model {

	// Create the constructor
    public function __construct() {
       parent::__construct();
    }

   public function introduction() {
        return array('introductionMessage' => 
                        'Hello and welcome to the SuperSmash Framework! <br />
                         This framework will help you while developing your application <br /><br />
                    ');
    }
}
?>