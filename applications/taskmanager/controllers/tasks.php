<?php
class Tasks extends System\SuperSmash\Controller { 
  
     // Create the constructor
    function __construct() {
        parent::__construct();
    }

    function _beforeAction() {

    }

    function start() {	
        session_start();
        if (isset($_SESSION['username'])){
            // Load the tasks Model
            $this->load->model('tasks');

            // Get the data from the database
            $data = $this->TasksModel->getTasks();
            
            // Load the view and add the data
            $this->load->view('tasks', $data);
        } else {
            header('Location: login');
        }
    }

    function _afterAction() {

    }
}
?>