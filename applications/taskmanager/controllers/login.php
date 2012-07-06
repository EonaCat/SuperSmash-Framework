<?php
class Login extends System\SuperSmash\Controller { 
  
     // Create the constructor
    function __construct() {
        parent::__construct();
    }

    function _beforeAction() {

    }

    function start($error = false) {	
        // Load the login Model
        $this->load->model('login');

        // Load the data for the login model
        $data = $this->LoginModel->login($error);
        
        // Load the view and add the data
        $this->load->view('login', $data);
    }

    function check(){
        // Load the login Model
        $this->load->model('login');
                
        if ($this->LoginModel->check()){
            die("ingelogd");
        } else {
            $this->start(true);
        }
    }

    function _afterAction() {

    }
}
?>