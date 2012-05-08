<?php
class Introduction extends System\SuperSmash\Controller { 
  
     // Create the constructor
    function __construct() {
        parent::__construct();
    }

    function _beforeAction() {

    }

    function start() {	
        // Load the introduction Model
        $this->load->model('introduction');

        // Load the data for the introduction model
        $data = $this->IntroductionModel->introduction();
        
        // Load the view and add the data
        $this->load->view('introduction', $data);
    }

    function _afterAction() {

    }
}
?>