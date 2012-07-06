<?php

class LoginModel extends System\SuperSmash\Model {

	// Create the constructor
    public function __construct() {
       parent::__construct();
    }

   public function login($error = false) {
        $array =  array('loginMessage' => 
                        'Use this form to login into the application.'
                        );

               if ($error) {
                  $error = array('errorMessage' => 
                                'Invalid username or password.'
                                );

                  $array = array_merge($array, $error);
                }
         return $array;
    }

    public function check(){
    	if ($_SERVER['REQUEST_METHOD'] == "POST"){
    		if (isset($_POST['username']) && isset($_POST['password'])){

      			// Get the database connection
      			$database = \System\SuperSmash\SuperSmash::database();

            $password = $_POST['password'];

            // Check if the username and password are valid
            $query = $database->prepare("SELECT * FROM login WHERE username = ? LIMIT 1");
            $query->execute(array($_POST['username']));
            $result = $query->fetch();
            $password = md5(sha1($password . $result['salt']));
            $password = $this->encrypt_login($password, $result['salt']);
          return $password == $result['password'];
    		}
    	}
    }

    private function encrypt_login($string, $key){
        $r = 0;
        for ($i = 0; $i < strlen($string); $i++)
        $r .= substr((md5($key)), ($i % strlen(md5($key))),
            1) . $string[$i];
        for ($i = 1; $i < strlen($r); $i++)
        $string[$i - 1] = chr(ord($r[$i - 1]) + ord(substr(md5
            ($key), ($i % strlen(md5($key))) - 1, 1)));
        $value = 0;
        $value = urlencode(base64_encode($string));
        return stripslashes($value);
    }
  }
?>