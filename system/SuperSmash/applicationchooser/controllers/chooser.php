<?php
class Chooser extends system\SuperSmash\Controller 
{ 
  
     // Create the constructor
    function __construct() 
    {
        parent::__construct();
    }

    function _beforeAction()
    {

    }

    function start() 
    {	
        // Load the chooser Model
        $this->load->model('chooser');

        // Load the data for the chooser model
        $data = array_merge($this->ChooserModel->denyList());
        $data += $this->ChooserModel->chooser();
        
        // Load the view and add the data
        $this->load->view('chooser', $data);
    }

    function _afterAction() 
    {

    }
}
?>