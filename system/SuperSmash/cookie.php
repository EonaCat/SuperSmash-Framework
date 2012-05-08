<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace System\SuperSmash;

class Cookie {

        private static $expire = 0;
        private static $path;
        private static $domain;
        private static $secure;
        private static $httponly = true;

        public static function init($domain = '', $path = '/', $secure = false, $httponly = false) {

                // Set the domain name to the current domain unless a domain name is given
                if (strlen($domain) == 0) $domain = self::getDomain($_SERVER['HTTP_HOST']);
                self::$domain = $domain;
                self::$path = $path;
                self::$secure = $secure;
                self::$httponly = $httponly;
        }

        private static function getDomain($url)
        {
            $myurl = str_replace('www.','',$url);
            $domain = parse_url($myurl);
         
            if(!empty($domain["host"])){
                 return $domain["host"];
            } else {
                 return $domain["path"];
                 }
        }

        // Set the cookie
        public static function set($name, $value = '', $force = false, $expire = NULL, $path = NULL, $domain = NULL, $secure = false, $httponly = false) {
                // Check if the $value is an integer
                if ($value === true || $value === false) {
                        $value = (int) $value;
                }

                // Set the cookie value
                if ($value) {
                        $value = base64_encode(serialize($value));
                        // Check the allowed cookie size
                        if (strlen($value) > (4 * 1024)){
                                trigger_error( "The cookie {$name} exceeds the specification for the maximum cookie size.  Some data may be lost", E_USER_WARNING );
                        }
                }

                // Force value into superglobal
                if ($force) {
                        $_COOKIE[$name] = $value;
                }

                // Set the cookie
                return setcookie($name, $value, (($expire) ? (time() + (int) $expire) : self::$expire), ($path) ? $path : self::$path, ($domain) ? $domain : self::$domain, ($secure) ? $secure : self::$secure, ($httponly) ? $httponly : self::$httponly);
        }

        // Check if the cookie exists
        public static function exists($name) {
                return isset($_COOKIE[$name]);
        }


        // Get a cookie value
        public static function get($name) {
                return (isset($_COOKIE[$name])) ? unserialize(base64_decode($_COOKIE[$name])) : NULL;
        }

        // Remove a cookie
        public static function remove($name, $force = false) {
                // Check if the cookie isset
                if (isset($_COOKIE[$name])) {
                        // Remove from superglobal
                        if ($force) {
                                unset($_COOKIE[$name]);
                        }

                        // Remove the cookie
                        return setcookie($name, '', time() - (3600 * 25), self::$path, self::$domain, self::$secure, self::$httponly);
                }
        }
}
Cookie::init();
?>