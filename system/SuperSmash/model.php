<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace System\SuperSmash;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Model
{
	// Create the contructor
    public function __construct() {
        $this->load = loadClass('Loader');
    }
}
?>
