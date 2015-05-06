<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   osticket
 * @author    Medialta
 * @license   GNU/LGPL
 * @copyright Medialta 2015
 */

namespace Contao;

/**
 * Class ModuleOsticket
 *
 * @copyright  Medialta 2015
 * @author     Medialta
 */
class ModuleOsticket extends Module
{
    protected $strTemplate = 'mod_osticket';

    protected function compile()
    {
        if (!$GLOBALS['TL_CONFIG']['osticket_api_url'] || !$GLOBALS['TL_CONFIG']['osticket_api_key']) {
            die('Please configure API key and API url in your backoffice.');
        }
        $use_captcha = false;
        if($GLOBALS['TL_CONFIG']['osticket_use_captcha']) { 
            $use_captcha = true;
            if (!$GLOBALS['TL_CONFIG']['osticket_captcha_key_public'] || !$GLOBALS['TL_CONFIG']['osticket_captcha_key_secret']) {
                die('Please configure captcha keys in your backoffice.');
            }
        }
        $placeholder = array(
            'name' => $GLOBALS['TL_LANG']['MSC']['osticket_name'],
            'mail' => $GLOBALS['TL_LANG']['MSC']['osticket_mail'],
            'phone' => $GLOBALS['TL_LANG']['MSC']['osticket_phone'],
            'subject' => $GLOBALS['TL_LANG']['MSC']['osticket_subject'],
            'message' => $GLOBALS['TL_LANG']['MSC']['osticket_message'],
            'submit' => $GLOBALS['TL_LANG']['MSC']['osticket_submit'],
            'success' => $GLOBALS['TL_LANG']['MSC']['osticket_success'],
            'captcha_error' => $GLOBALS['TL_LANG']['MSC']['osticket_captcha_error'],
        );

        if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['subject']) && isset($_POST['message'])) {
            $name = Input::post('name');
            $email = Input::post('email');
            $phone = Input::post('phone');
            $subject = Input::post('subject');
            $message = Input::post('message');

            if ($use_captcha) {
                $captcha = Input::post('g-recaptcha-response');
                $postdata = http_build_query(
                    array(
                        'secret' => $GLOBALS['TL_CONFIG']['osticket_captcha_key_secret'],
                        'response' => $captcha
                    )
                );

                $opts = array('http' =>
                    array(
                        'method'  => 'POST',
                        'header'  => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postdata
                    )
                );

                $context  = stream_context_create($opts);

                $result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
                $result_json = json_decode($result, true);
                if ($result_json['success']) {

                    $config = array(
                        'url'=>$GLOBALS['TL_CONFIG']['osticket_api_url'],
                        'key'=>$GLOBALS['TL_CONFIG']['osticket_api_key']
                    );
                    if($config['url'] === 'http://your.domain.tld/api/tickets.json') {
                        echo "<p style=\"color:red;\"><b>Error: No URL</b><br>You have not configured this script with your URL!</p>";
                        die();  
                    }       
                    if($this->IsNullOrEmptyString($config['key']) || ($config['key'] === 'PUTyourAPIkeyHERE'))  {
                        echo "<p style=\"color:red;\"><b>Error: No API Key</b><br>You have not configured this script with an API Key!</p>";
                        die();
                    }
                    $data = array(
                        'name'      =>      $name, 
                        'email'     =>      $email,
                        'phone'     =>      $phone,
                        'subject'   =>      $subject,
                        'message'   =>      $message,
                        'ip'        =>      $_SERVER['REMOTE_ADDR'],
                        'topicId'   =>      '1',
                        'attachments' => array()
                    );

                    function_exists('curl_version') or die('CURL support required');
                    function_exists('json_encode') or die('JSON support required');

                    set_time_limit(30);

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $config['url']);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.8');
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    $result=curl_exec($ch);
                    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($code != 201)
                        die('Unable to create ticket: '.$result);

                    $ticket_id = (int) $result;

                    $this->Template->afficheForm=false;
                } else {
                    $this->Template->afficheForm=true;
                    $this->Template->errorCaptcha = $placeholder['captcha_error'];
                    $this->Template->nom = $name;
                    $this->Template->email = $email;
                    $this->Template->phone = $phone;
                    $this->Template->subject = $subject;
                    $this->Template->message = $message;
                }
            } else {

                $config = array(
                    'url'=>$GLOBALS['TL_CONFIG']['osticket_api_url'],
                    'key'=>$GLOBALS['TL_CONFIG']['osticket_api_key']
                );
                if($config['url'] === 'http://your.domain.tld/api/tickets.json') {
                    echo "<p style=\"color:red;\"><b>Error: No URL</b><br>You have not configured this script with your URL!</p>";
                    die();  
                }       
                if($this->IsNullOrEmptyString($config['key']) || ($config['key'] === 'PUTyourAPIkeyHERE'))  {
                    echo "<p style=\"color:red;\"><b>Error: No API Key</b><br>You have not configured this script with an API Key!</p>";
                    die();
                }
                $data = array(
                    'name'      =>      $name, 
                    'email'     =>      $email,
                    'phone'     =>      $phone,
                    'subject'   =>      $subject,
                    'message'   =>      $message,
                    'ip'        =>      $_SERVER['REMOTE_ADDR'],
                    'topicId'   =>      '1',
                    'attachments' => array()
                );

                function_exists('curl_version') or die('CURL support required');
                function_exists('json_encode') or die('JSON support required');

                set_time_limit(30);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $config['url']);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.8');
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $result=curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($code != 201)
                    die('Unable to create ticket: '.$result);

                $ticket_id = (int) $result;

                $this->Template->afficheForm = false;
                $this->Template->useCaptcha = $use_captcha;
                if ($use_captcha) $this->Template->publicKey = $GLOBALS['TL_CONFIG']['osticket_captcha_key_public'];
            }
            
        } else {
            $this->Template->afficheForm = true;
            $this->Template->useCaptcha = $use_captcha;
            if ($use_captcha) $this->Template->publicKey = $GLOBALS['TL_CONFIG']['osticket_captcha_key_public'];
        }
        $this->Template->placeholder = $placeholder;
    }

    protected function IsNullOrEmptyString($question){
        return (!isset($question) || trim($question)==='');
    }
}