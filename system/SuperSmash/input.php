<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace system\SuperSmash;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Input
{
    
    // This variable will hold the cookie expiration time
    protected $time;

    // This variable will hold the cookie path
    protected $cookiePath;

    // This variable will hold the cookie domain
    protected $cookieDomain;

    // This variable will hold the user agent of the user
    protected $userAgent = false;

    // This variable will hold the ipaddress of the user
    protected $ipAddress = false;

    // This variable will hold the Array of tags and attributes
    protected $tagsArray = array();
    protected $attributesArray = array();

    // This variable will hold the tagging methods
    protected $tagsMethod = 0;
    protected $attributesMethod = 0;

    // This variable will hold the activation of the xss autocleaner
    protected $xssAuto = 1;

    // This variable will hold an array with the Blacklisting of tags and attributes
    protected $tagBlackList = array('applet', 'body', 'bgsound', 
                                        'base', 'basefont', 'embed', 'frame', 'frameset', 'head', 'html', 'id', 'iframe', 
                                        'ilayer', 'layer', 'link', 'meta', 'name', 'object', 'script', 'style', 'title', 'xml'
                                    );
    protected $attributesBlackList = array('action', 'background', 'codebase', 'dynsrc', 'lowsrc');

    // Create the constructor
    public function __construct() 
    {
        // Set the cookie defaults
        $this->time = ( time() + (60 * 60 * 24 * 365) );        // Default: 1 year
        $this->cookiePath =  "/";
        $this->cookieDomain = rtrim($_SERVER['HTTP_HOST'], '/');
    }

    // This function will return the post variable
    public function post($var, $xss = false)
    {
        if(isset($_POST[$var])) 
        {
            if(!$xss) 
            {
                return $_POST[$var];
            }
            return $this->clean($_POST[$var]);
        }
        return false;
    }
    
    // This function will return the get variable
    public function get($var, $xss = false)
    {
        if(isset($_GET[$var]))
        {
            if(!$xss) 
            {
                return $this->cleanElement($_GET[$var]);
            }
            return $this->cleanElement($this->clean($_GET[$var]));
        }
        return false;
    }

    public function cleanElement($variable) 
    { 
        if(!is_array($variable))
            $variable = htmlentities($variable,ENT_QUOTES,"UTF-8"); 
        else 
            foreach ($variable as $key => $value) 
                $variable[$key] = $this->clean($value); 
        return $variable; 
    } 

    // This function will return the cookie variable
    public function cookie($name, $xss = false) 
    {
        if (\system\SuperSmash\Cookie::exists($name)){
            if(!$xss) {
                return \system\SuperSmash\Cookie::get($name);
            }
            return $this->clean(\system\SuperSmash\Cookie::get($name));
        }
        return false;
    }

    // This function will set the cookie variable
    function setCookie($cookieName, $cookieValue, $cookieTime = null) 
    {
        if($cookieTime === null) 
        {
            $cookieTime = $this->time;
        }
        \system\SuperSmash\Cookie::set($cookieName, $cookieValue, false, $cookieTime,$this->cookiePath);
    }

    // This function will return the user agent of the user
    public function userAgent() 
    {
        if(!$this->userAgent) 
        {
            $this->userAgent = (isset($_SERVER['HTTP_userAgent']) ? $_SERVER['HTTP_userAgent'] : false);
        }
        return $this->userAgent;
    }

    // This function will return the ipAddress of the user
    public function ipAddress() 
    {
        
        // Return it if we already determined the IP
        if(!$this->ipAddress) 
        {       
            
            // Check to see if the server has the IP address
            if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') 
            {
                $this->ipAddress = $_SERVER['REMOTE_ADDR'];
            }
            elseif(isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '') 
            {
                $this->ipAddress = $_SERVER['HTTP_CLIENT_IP'];
            }

            // If we still have a false IP address, then set to 0's
            if (!$this->ipAddress) 
            {
                $this->ipAddress = '0.0.0.0';
            }
        }
        return $this->ipAddress;
    }

    // This function will set the cleaning rules
    public function setRules($tagsArray = array(), $attributesArray = array(), $tagsMethod = 0, $attributesMethod = 0, $xssAuto = 1) 
    {	
        // Count how many are in each for out loops
        $countTags = count($tagsArray);
        $countAttributes = count($attributesArray);
        
        // Loop through and lowercase all Tags
        for($i = 0; $i < $countTags; $i++) 
        {
            $tagsArray[$i] = strtolower($tagsArray[$i]);
        }
        
        // Loop through and lowercase all attributes
        for($i = 0; $i < $countAttributes; $i++) 
        {
            $attributesArray[$i] = strtolower($attributesArray[$i]);
        }
        
        // Set the class variables
        $this->tagsArray = $tagsArray;
        $this->attributesArray = $attributesArray;
        $this->tagsMethod = $tagsMethod;
        $this->attributesMethod = $attributesMethod;
        $this->xssAuto = $xssAuto;
    }

    // This function will clean the given input
    public function clean($source) 
    {
        
        // If in array, clean each value
        if(is_array($source))
        {
            foreach($source as $key => $value) 
            {
                if(is_string($value))
                {
                    // filter element for XSS and other 'bad' code etc.
                    $source[$key] = $this->remove($this->decode($value));
                }
            }
            return $source;
        } 
        elseif(is_string($source)) 
        {
            // filter element for XSS and other 'bad' code etc.
            return $this->remove($this->decode($source));
        } 
        return $source;
    }

    // This function will remove unwanted tags
    protected function remove($source) 
    {
        $loopCounter = 0;
        while($source != $this->filterTags($source))
        {
            $source = $this->filterTags($source);
            $loopCounter++;
        }
        return $source;
    }

    // This function will strip certain tags of the string   
    protected function filterTags($source) 
    {
        $preTag = null;
        $postTag = $source;
        
        // find initial tag's position
        $tagOpen_start = strpos($source, '<');
        
        // interate through string until no tags left
        while($tagOpen_start !== false) 
        {
            // process tag interatively
            $preTag .= substr($postTag, 0, $tagOpen_start);
            $postTag = substr($postTag, $tagOpen_start);
            $fromTagOpen = substr($postTag, 1);
            $tagOpen_end = strpos($fromTagOpen, '>');
            if($tagOpen_end === false)
            {
                break;
            }
            
            // next start of tag (for nested tag assessment)
            $tagOpen_nested = strpos($fromTagOpen, '<');
            if(($tagOpen_nested !== false) && ($tagOpen_nested < $tagOpen_end))
            {
                $preTag .= substr($postTag, 0, ($tagOpen_nested + 1));
                $postTag = substr($postTag, ($tagOpen_nested + 1));
                $tagOpen_start = strpos($postTag, '<');
                continue;
            } 

            $tagOpen_nested = (strpos($fromTagOpen, '<') + $tagOpen_start + 1);
            $currentTag = substr($fromTagOpen, 0, $tagOpen_end);
            $tagLength = strlen($currentTag);
            
            if(!$tagOpen_end) 
            {
                $preTag .= $postTag;
                $tagOpen_start = strpos($postTag, '<');			
            }
            
            // iterate through tag finding attribute pairs - setup
            $tagLeft = $currentTag;
            $attributeSet = array();
            $currentSpace = strpos($tagLeft, ' ');
            
            // is end tag
            if(substr($currentTag, 0, 1) == "/")
            {
                $isCloseTag = true;
                list($tagName) = explode(' ', $currentTag);
                $tagName = substr($tagName, 1);
            } 
            else 
            {
                $isCloseTag = false;
                list($tagName) = explode(' ', $currentTag);
            }	

            // excludes all "non-regular" tagnames OR no tagname OR remove if xssauto is on and tag is blacklisted
            if((!preg_match("/^[a-z][a-z0-9]*$/i", $tagName)) || (!$tagName) || ((in_array(strtolower($tagName),
                $this->tagBlackList)) && ($this->xssAuto)))
            { 

                $postTag = substr($postTag, ($tagLength + 2));
                $tagOpen_start = strpos($postTag, '<');
                continue;
            }
            
            // this while is needed to support attribute values with spaces in!
            while($currentSpace !== false)
            {
                $fromSpace = substr($tagLeft, ($currentSpace+1));
                $nextSpace = strpos($fromSpace, ' ');
                $openQuotes = strpos($fromSpace, '"');
                $closeQuotes = strpos(substr($fromSpace, ($openQuotes+1)), '"') + $openQuotes + 1;
                
                // another equals exists
                if(strpos($fromSpace, '=') !== false)
                {
                    // opening and closing quotes exists
                    if(($openQuotes !== false) && (strpos(substr($fromSpace, ($openQuotes+1)), '"') !== false)) 
                    {
                        $attr = substr($fromSpace, 0, ($closeQuotes+1));
                    } 
                    else  
                    {
                        $attr = substr($fromSpace, 0, $nextSpace);
                    }
                } 
                else 
                {
                    $attr = substr($fromSpace, 0, $nextSpace);
                }
                
                if(!$attr) 
                {
                    $attr = $fromSpace;
                }
                
                // add to attribute pairs array
                $attributeSet[] = $attr;
                
                // next inc
                $tagLeft = substr($fromSpace, strlen($attr));
                $currentSpace = strpos($tagLeft, ' ');
            }
            
            // appears in array specified by user
            $tagFound = in_array(strtolower($tagName), $this->tagsArray);

            // remove this tag on condition			
            if((!$tagFound && $this->tagsMethod) || ($tagFound && !$this->tagsMethod)) 
            {
                // reconstruct tag with allowed attributes
                if(!$isCloseTag) 
                {
                    $attributeSet = $this->filterAttribute($attributeSet);
                    $preTag .= '<' . $tagName;
                    for($i = 0; $i < count($attributeSet); $i++) 
                    {
                        $preTag .= ' ' . $attributeSet[$i];
                    }
                    
                    // reformat single tags to XHTML
                    if(strpos($fromTagOpen, "</" . $tagName))
                    {
                        $preTag .= '>';
                    } 
                    else 
                    {
                        $preTag .= ' />';
                    }
                } 
                else  
                {
                    $preTag .= '</' . $tagName . '>';
                }
            }
            
            // find next tag's start
            $postTag = substr($postTag, ($tagLength + 2));
            $tagOpen_start = strpos($postTag, '<');			
        }
        
        // append any code after end of tags
        $preTag .= $postTag;
        return $preTag;
    }

    // This function will strip certain tags off attributes
    protected function filterAttribute($attributeSet) 
    {	
        $newSet = array();
        
        // process attributes
        for($i = 0; $i <count($attributeSet); $i++) 
        {
            // skip blank spaces in tag
            if(!$attributeSet[$i]) 
            {
                continue; 
            }
            
            // split into attr name and value
            $attrSubSet = explode('=', trim($attributeSet[$i]));
            list($attrSubSet[0]) = explode(' ', $attrSubSet[0]);
            
            // removes all "non-regular" attr names AND also attr blacklisted
            if ((!preg_match("/^[a-z]*$/i", $attrSubSet[0])) || (($this->xssAuto) && ((in_array(strtolower($attrSubSet[0]), 
            $this->attributesBlackList)) || (substr($attrSubSet[0], 0, 2) == 'on')))) 
            {
                continue;
            }
            
            // xss attr value filtering
            if($attrSubSet[1]) 
            {
                // strips unicode, hex, etc
                $attrSubSet[1] = str_replace('&#', '', $attrSubSet[1]);
                
                // strip normal newline within attr value
                $attrSubSet[1] = preg_replace('/\s+/', '', $attrSubSet[1]);
                
                // strip double quotes
                $attrSubSet[1] = str_replace('"', '', $attrSubSet[1]);
                
                // [requested feature] convert single quotes from either side to doubles (Single quotes shouldn't be used to pad attr value)
                if ((substr($attrSubSet[1], 0, 1) == "'") && (substr($attrSubSet[1], (strlen($attrSubSet[1]) - 1), 1) == "'")) 
                {
                    $attrSubSet[1] = substr($attrSubSet[1], 1, (strlen($attrSubSet[1]) - 2));
                }
                
                // strip slashes
                $attrSubSet[1] = stripslashes($attrSubSet[1]);
            }
            
            // auto strip attr's with "javascript:
            if(((strpos(strtolower($attrSubSet[1]), 'expression') !== false) && (strtolower($attrSubSet[0]) == 'style')) 
                || (strpos(strtolower($attrSubSet[1]), 'javascript:') !== false)
                || (strpos(strtolower($attrSubSet[1]), 'behaviour:') !== false) 
                || (strpos(strtolower($attrSubSet[1]), 'vbscript:') !== false) 
                || (strpos(strtolower($attrSubSet[1]), 'mocha:') !== false)
                || (strpos(strtolower($attrSubSet[1]), 'livescript:') !== false) 
            ) continue;
            
            // if matches user defined array
            $attrFound = in_array(strtolower($attrSubSet[0]), $this->attributesArray);
            
            // keep this attr on condition
            if((!$attrFound && $this->attributesMethod) || ($attrFound && !$this->attributesMethod))
            {
                // attr has value
                if($attrSubSet[1]) 
                {
                    $newSet[] = $attrSubSet[0] . '="' . $attrSubSet[1] . '"';
                }
                
                // attr has decimal zero as value
                elseif($attrSubSet[1] == "0") 
                {
                    $newSet[] = $attrSubSet[0] . '="0"';
                }            
                
                // reformat single attributes to XHTML
                else 
                {
                    $newSet[] = $attrSubSet[0] . '="' . $attrSubSet[0] . '"';
                }
            }	
        }
        return $newSet;
    }

    // This function will decode the source to a clean string
    protected function decode($source) 
    {
        $source = html_entity_decode($source, ENT_QUOTES, "ISO-8859-1");
        $source = preg_replace('/&#(\d+);/me',"chr(\\1)", $source);
        $source = preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)", $source);
        return $source;
    }
}
?>