<?php

/*
 * Email Template - Frog CMS behaviour
 *
 * Copyright (c) 2008 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/
 *
 */

class Email_template {
    
    public function __construct(&$page, $params) {
        $this->page   = &$page;
        $this->params = $params;
        if ($status = $this->send()) {
            /* Redirect to success URL. */
            if (trim($_POST['success'])) {
                header('Location: ' . $_POST['success']);
            } else {
                /* Or display the mail which was sent. Usefull for debugging. */
                print '<pre>' . htmlentities($this->content()) . '</pre>';
                die();
            }
        } else {
            /* Redirect failure URL. */
            if (trim($_POST['failure'])) {
                header('Location: ' . $_POST['failure']);
            } else {
                /* Or dump of status. */
                /* TODO This shoule be more usefull. */                
                print '<pre>';
                print "Sending email failed.\n";
                print '</pre>';
                var_dump($status);                
            }
        }
        die();
    }
    
    public function send() {
        $header_array  = $this->headers();
        $header_string = '';

        $to            = $header_array['To'];
        $subject       = $header_array['Subject'];
        
        /* Avoid double headers. */
        unset($header_array['To']);
        unset($header_array['Subject']);
        
        /* mail() wants extra headers as string. */
        foreach($header_array as $key => $value) {
            $header_string .= "$key: $value \r\n"; 
        }        
        return mail($to, $subject, $this->body(), $header_string);
    }
    
    public function content() {
        ob_start();
        $this->page->_executeLayout();
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
    
    public function headers() {
        $content    = $this->content();
        $retval     = array();
        $line_array = explode("\n", $content);
        /* First line which is not format "Name: Data" will assume end of headers. */
        foreach($line_array as $line) {
            $data = split(':', $line);
            if (trim($data[1])) {
                $retval[trim($data[0])] = trim($data[1]);                
            } else {
                break;
            }
        }
        return $retval;
    }
    
    public function body() {
        $content    = $this->content();
        $retval     = array();
        $line_array = explode("\n", $content);
        /* This way we do not have \n vs \r\n problems. */
        foreach($line_array as $key => $line) {
            if (trim($line)) {
                unset($line_array[$key]);
            } else {
                break;
            }
        }
        return implode($line_array, "\n");
    }
    
}




