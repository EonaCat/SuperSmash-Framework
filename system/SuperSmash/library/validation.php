<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace system\library;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Validation
{
    // Our fields
    protected $fields;

    // Our field rules
    protected $field_rules;

    // A bool of whether we are debugging
    protected $debug;

    // Our running list of errors
    protected $errors;
 
    // Create the constructor
    public function __construct()
    {
        // Init the default values
        $this->fields = $_POST;
        $this->field_rules = array();
        $this->errors = array();
    }

    // This function is used to set the rules of certain $_POST vars
    public function set($rules) 
    {
        if(!is_array($rules)) 
        {
            showError('no_array', array('rules', 'Validation::set'), E_ERROR);
        }
        
        // Add the current rules
        $this->field_rules = array_merge($this->field_rules, $rules);
        
        // Allow chaining here
        return $this;
    }

    // This function validates all the POST data that has rules set
    public function validate($debug = false)
    {
        // before we begin, make sure we have post data
        if(!empty($this->field_rules)) 
        {
            // Set our debugging
            $this->debug = $debug;
            
            // Validate each of the fields that have rules
            foreach($this->field_rules as $field => $rules) 
            {
                // Get our array of rules to process
                $rules = explode('|', $rules);
                
                // Make sure that the field we are looking at exists
                if(isset($this->fields[$field])) 
                {
                    // Process each rule for this post var
                    foreach($rules as $rule)
                    {
                        $result = null;
                        
                        // We will define the param as false, if preg_match
                        // finds a second value, then it will overwrite this
                        $param = false;

                        if (preg_match("/^(.*?)\[(.*?)\]$/", $rule, $match)) 
                        {
                            $rule = $match[1];
                            $param = $match[2];
                        }

                        // Call the function that corresponds to the rule
                        if (!empty($rule))
                        {
                            $result = $this->$rule($this->fields[$field], $param);  
                        } 

                        // Handle errors
                        if ($result === false)
                        {
                            $this->set_error($field, $rule);  
                        } 
                    }
                }
            }
            return (empty($this->errors));
        }
    }
 
    // This function returns an array of all the errors by field name
    public function get_errors() {
        if(count($this->errors) == 0) 
        {
            return array();
        }
        return $this->errors;
    }

    // This function sets an error for the $field
    protected function set_error($field, $rule) 
    {
        // If debugging, we want an array of all failed validations
        if($this->debug) 
        {
            if(isset($this->errors[$field])) 
            {
                $this->errors[$field] .= "|".$rule;
                return;
            }
            $this->errors[$field] = $rule;
            return;
        }
        $this->errors[$field] = true;
    }

    // This function determines if the string passed has any values
    public function required($string, $value = false) 
    {
        if (!is_array($string)) 
        {
            // Trim white space and see if its still empty
            $string = trim($string);
        }
        return (!empty($string));
    }

    // This function determines if the string is a valid email
    public function email($string) 
    {
        if(filter_var($string, FILTER_VALIDATE_EMAIL)) 
        {
            return true;
        }
        return false;
    }

    // This function determines if the string passed is numeric
    public function number($string) 
    {
        return (is_numeric($string));
    }

    // This function determines if the string passed is valid URL
    public function url($string) 
    {
        return (!preg_match('@^(mailto|ftp|http(s)?)://(.*)$@i', $string)) ? false : true;
    }

    // This function determines if the string passed is a float
    public function float($string) 
    {
        return (is_float($string));
    }

    // This function determines if the string passed has a minimum value  of $value
    public function min($string, $value) 
    {
        if(!is_numeric($string)) 
        {
            return (strlen($string) >= $value);
        }
        return ($string >= $value);
    }

    // This function determines if the string passed has a maximum value of $value
    public function max($string, $value) 
    {
        if(!is_numeric($string)) 
        {
            return (strlen($string) <= $value);
        }
        return ($string <= $value);
    }

    // This function determines if the string passed contains the specified pattern
    public function pattern($string, $pattern) 
    {
        return (!preg_match("/".$pattern."/", $string)) ? false : true;
    }
}
?>