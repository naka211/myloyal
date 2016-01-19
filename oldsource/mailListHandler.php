<?php
if(isset($_REQUEST['premio_debug'])) {
    error_reporting(-1);
    ini_set('display_errors', 1);
}

if( isset($_REQUEST['premio_debug']) && function_exists('register_shutdown_function') ) {
    function minigo_fatal_handler() {
        $errfile = "unknown file";
        $errstr  = "shutdown";
        $errno   = E_CORE_ERROR;
        $errline = 0;

        $error = error_get_last();

        if( $error !== NULL) {
            $errno   = $error["type"];
            $errfile = $error["file"];
            $errline = $error["line"];
            $errstr  = $error["message"];
        }

        echo minigo_format_error( $errno, $errstr, $errfile, $errline );
    }

    function minigo_format_error( $errno, $errstr, $errfile, $errline ) {
        $trace = print_r( debug_backtrace( false ), true );

        $content  = "<table><thead bgcolor='#c8c8c8'><th>Item</th><th>Description</th></thead><tbody>";
        $content .= "<tr valign='top'><td><b>Error</b></td><td><pre>$errstr</pre></td></tr>";
        $content .= "<tr valign='top'><td><b>Errno</b></td><td><pre>$errno</pre></td></tr>";
        $content .= "<tr valign='top'><td><b>File</b></td><td>$errfile</td></tr>";
        $content .= "<tr valign='top'><td><b>Line</b></td><td>$errline</td></tr>";
        $content .= "<tr valign='top'><td><b>Trace</b></td><td><pre>$trace</pre></td></tr>";
        $content .= '</tbody></table>';

        return $content;
    }

    register_shutdown_function('minigo_fatal_handler');
}

try {
    // Future-friendly json_encode
    if( !function_exists('json_encode') ) {
        require_once( 'inc/JSON.php' );
        function json_encode($data) {
            $json = new Services_JSON();
            return( $json->encode($data) );
        }
    }

    // Future-friendly json_decode
    if( !function_exists('json_decode') ) {
        require_once( 'inc/JSON.php' );
        function json_decode($data) {
            $json = new Services_JSON();
            return( $json->decode($data) );
        }
    }

    // mb_encode_mimeheader fallback
    if( !function_exists('mb_encode_mimeheader') ) {
        function mb_encode_mimeheader($data) {
            return $data;
        }
    }

    if(!class_exists('Drewm_MailChimp')) {
        require_once( 'inc/MailChimp.class.php' );
    }

    //If the form is submitted

    function minigoMailHandler() {

        // Anti spam honeypot field
        if(!empty($_POST['important-info']) || !empty($_POST['subscribe']) || !empty($_POST['contact'])) {
            throw new Exception('Initial field check failed');
            exit;
        }

        if(!isset($_POST['important-info2']) || !empty($_POST['important-info2'])) {
            throw new Exception('Second initial field check failed');
            exit;
        }

        require_once( 'config.php' );

        if(isset($_POST['subscribe']) && isset($_POST['email'])) {
            $email = trim($_POST['email']);

            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //if(preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/', trim($_POST['email']))) {

                if(!$use_Mailchimp) {
                    $file = $maillist_file;
                    $content = file_get_contents($file);

                    if(stripos($content, $_POST['email']) !== false) {
                        echo 'success';
                        exit;
                    }

                    file_put_contents($file, $content.$_POST['email']."\n");
                    echo 'success';
                    exit;
                } else {
                    $MailChimp = new Drewm_MailChimp($mailchimp_API_Key);
                    //print_r($MailChimp->call('lists/list'));

                    $result = $MailChimp->call('lists/subscribe', array(
                                    'id'                => trim($mailchimp_list_ID),
                                    'email'             => array('email'=>$email),
                                    //'merge_vars'        => array('FNAME'=>'Davy', 'LNAME'=>'Jones'),
                                    'double_optin'      => $mailchimp_double_optin,
                                    'update_existing'   => true,
                                    'replace_interests' => false,
                                    'send_welcome'      => $mailchimp_send_welcome,
                                ));

                    if(!empty($result['email']) && !empty($result['euid']) && !empty($result['leid'])) {
                        echo 'success';
                        exit;
                    }
                    throw new Exception("MailChimp request failed\n\n".json_encode($result));
                    exit;
                }
            } else {
                throw new Exception('Email validation failed');
                exit;
            }
        }

        if(isset($_POST['contact']) && isset($_POST['email'])) {

        // require a name from user
        if(trim($_POST['name']) === '') {
            throw new Exception('Name field empty');
            exit;
        } else {
            $name = htmlspecialchars(strip_tags(trim($_POST['name'])), ENT_QUOTES, 'utf-8');
        }

        // need valid email
        if(trim($_POST['email']) === '')  {
            $emailError = 'Forgot to enter in your e-mail address.';
            throw new Exception('Email field empty');
            exit;
        } elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email validation check failed 2');
            exit;
        } else {
            $email = trim($_POST['email']);
        }

        // we need at least some content
        if(trim($_POST['message']) === '') {
            throw new Exception('Message field empty');
            exit;
        } else {
            $message = htmlspecialchars(strip_tags($_POST['message']), ENT_QUOTES, 'utf-8');
        }

        // upon no failure errors let's email now!
        $from_address = trim($from_address);
        $target_address = trim($target_address);

        $emailTo = $target_address;
        $subject = trim($subject_prefix).' '.$name;
        $body = "Name: $name \n\nEmail: $email \n\nMessage: $message";

        $replyTo = $email;
        $emailFrom = mb_encode_mimeheader($name) . '" <' . (empty($from_address) ? $target_address : $from_address) . '>';

        $headers = array
        (
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
            'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>',
            'From: ' . $emailFrom,
            'Reply-To: ' . $replyTo,
            'X-Mailer: PHP v' . phpversion(),
            'X-Originating-IP: ' . $_SERVER['SERVER_ADDR'],
        );

        $mail = mail($emailTo, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));

        if($mail) {
            echo "success";
            exit;
        }

        throw new Exception('Mail function failed');
        exit;
        }
    }

    minigoMailHandler();
} catch(Exception $e) {
    if(isset($_REQUEST['premio_debug'])) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}