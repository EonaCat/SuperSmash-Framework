<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace System\Library;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Email {
    // This variable will hold the email recipient
    protected $to = '';
    
    // This variable will hold an Array of carbon copy's
    protected $cc = array();
    
    // This variable will hold an array of blind carbon copy's
    protected $bcc = array();
    
    // This variable will hold the email subject
    protected $subject = '';
    
    // This variable will hold the email message
    protected $message = '';
    
    // This variable will hold an array of attachment data
    protected $attachment = array();
    
    // This variable will hold the email character Set
    protected $charset = 'ISO-8859-1';
    
    // This variable will hold the email boundary
    protected $boundary = '';
    
    // This variable will hold the email header data
    protected $header = '';
    
    // This variable will hold the textHeader
    protected $textheader = '';
    
    // This variable will hold an array of errors
    public $errors = array();

    // Create the contructor     
    public function __construct() {
        // Set our email boundary
        $this->boundary = uniqid(time());
    }
    
    // This function will send the email    
    public function send() {
        $this->build_header();
        return mail($this->to, $this->subject, $this->message, $this->header);
    }

    // This function will add a to recipient to the email message
    public function to($email, $name = null) { 
        // Check if the email is valid before adding it
        if(!$this->validate($email)){
          return false;  
        } 
        
        if($name == null) {
            $this->to = $email;
        } else {
            $this->to = $name." <".$email.">";
        }
        return true;
    }

    // This function will add a to sender to the email message 
    public function from($email, $name = null) { 
        // Check if the email is valid before adding it
        if(!$this->validate($email)) {
            return false;   
        }
        
        if($name == null) {
            $this->header .= "From: ".$email."\r\n";
        } else {
            $this->header .= "From: ".$name." <".$email.">\r\n";
        }
        return true;
    }
    
    // This function will add a reply to to the email message
    public function reply_to($email, $name = null) { 
        // Check if the email is valid before adding it
        if(!$this->validate($email)){
            return false;  
        } 
        
        if($name == null) {
            $this->header .= "Reply-to: ".$email."\r\n";
        } else {
            $this->header .= "Reply-to: ".$name." <".$email.">\r\n";
        }
        return true;
    }

    // This function will add a cc to to the email message   
    public function cc($email) {
        // Check if the email is valid before adding it
        if(!$this->validate($email)){
            return false;  
        } 
        
        $this->cc[] = $email;
        return true;
    }

    // This function will add a bcc to to the email message    
    public function bcc($email) { 
        // Check if the email is valid before adding it
        if(!$this->validate($email)) {
            return false;
        }
        
        $this->bcc[] = $email;
        return true;
    }

    // This function will set the email subject    
    public function subject($subject) {
        $this->subject = strip_tags(trim($subject));
        return true;
    }

    // This function will add the message to the headers so we can actually send the email
    public function message($message = '', $type = 'html') {
        $textboundary = uniqid('textboundary');
        $this->textheader = "Content-Type: multipart/alternative; boundary=\"".$textboundary."\"\r\n\r\n";
        $this->message .= "--". $textboundary ."\r\n";
        $this->message .= "Content-Type: text/plain; charset=\"". $this->charset ."\"\r\n";
        $this->message .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
        $this->message .= strip_tags($message) ."\r\n\r\n";
        $this->message .= "--". $textboundary ."\r\n";
        $this->message .= "Content-Type: text/html; charset=\"".$this->charset ."\"\r\n";
        $this->message .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
        $this->message .= $message ."\r\n\r\n";
        $this->message .= "--". $textboundary ."--\r\n\r\n";
    }

    // This function will add an attachment to the email message 
    public function attachment($file) {
        // Make sure we are dealing with a real file here
        if(is_file($file)) {
            $basename = basename($file);
            $attachmentheader = "--". $this->boundary ."\r\n";
            $attachmentheader .= "Content-Type: ".$this->mime_type($file)."; name=\"".$basename."\"\r\n";
            $attachmentheader .= "Content-Transfer-Encoding: base64\r\n";
            $attachmentheader .= "Content-Disposition: attachment; filename=\"".$basename."\"\r\n\r\n";
            $attachmentheader .= chunk_split(base64_encode(fread(fopen($file,"rb"),filesize($file))),72)."\r\n";
            $this->attachment[] = $attachmentheader;
        } else {
            return false;
        }
    }

    // This function builds the email header before being sent
    protected function build_header() {
        // Add out Cc's
        $count = count($this->cc);
        if($count > 0) {
            $this->header .= "Cc: ";
            for($i=0; $i < $count; $i++) {
                // Add comma if we are not on our first!
                if($i > 0) {
                    $this->header .= ',';   
                }
                $this->header .= $this->cc[$i];
            }
            $this->header .= "\r\n";
        }
        
        // Add out Bcc's
        $count = count($this->bcc);
        if($count > 0) {
            $this->header .= "Bcc: ";
            for($i=0; $i < $count; $i++) {
                // Add comma if we are not on our first!
                if($i > 0){
                    $this->header .= ',';  
                } 
                $this->header .= $this->bcc[$i];
            }
            $this->header .= "\r\n";
        }
        
        // Add our MINE version and X-Mailer
        $this->header .= "X-Mailer: SuperSmash Framework\r\n";
        $this->header .= "MIME-Version: 1.0\r\n";
        
        // Add attachments
        $attachcount = count($this->attachment);
        if($attachcount > 0) {
            $this->header .= "Content-Type: multipart/mixed; boundary=\"". $this->boundary ."\"\r\n\r\n";
            $this->header .= "--". $this->boundary ."\r\n";
            $this->header .= $this->textheader;

            if($attachcount > 0){
              $this->header .= implode("", $this->attachment);  
            } 
            $this->header .= "--". $this->boundary ."--\r\n\r\n";
        } else {
            $this->header .= $this->textheader;
        }
    }
    
    // This function will check if the emailAddress specified is a valid email address
    public function validate($email) {
        // Use PHP's built in email validator
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Invalid Email: <". $email .">";
            return false;
        }
        return true;
    }

    // This function will get the mime type of a file for attachments
    public function mime_type($file) {
        $finfo = new finfo();
        return $finfo->file($file, FILEINFO_MIME);
    }

    // This function will clear the current email
    public function clear() {
        $this->header = null;
        $this->to = null;
        $this->subject = null;
        return true;
    }
}
?>