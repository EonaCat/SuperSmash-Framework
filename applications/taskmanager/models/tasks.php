<?php

class TasksModel extends System\SuperSmash\Model {

  // Create the constructor
    public function __construct() {
       parent::__construct();
    }

   public function getTasks() {
        $array =  array('welcomeMessage' => 
                        'Welcome %s to the task manager'
                        );

        // Get the database connection
        $database = \System\SuperSmash\SuperSmash::database();

        $query = $database->prepare("SELECT * FROM tasks WHERE assigned_to = ?");
        $query->execute(array($_SESSION['username']));
        $result = $query->fetch();

        if ($result){
          $array = array_merge($array, $result);
        }

        return $array;
    }
  }
?>