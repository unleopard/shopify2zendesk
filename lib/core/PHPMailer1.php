<?php
/**
 * old mailer, please use phpmailer
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: phpmailer
 */
class PHPMailer
{

    private $mailer_name = "smartmail";
    private $mailer_version = "1.0";

    private $to_mail = null;
    private $to_name = null;
    private $from = null;
    private $sender = null;
    private $sujet = null;
    private $site_name = null;


    private $is_html = false;
    private $precedence = null; //list, junk, or bulk
    /**
     * Email priority (1 = High, 3 = Normal, 5 = low).
     * @var int
     */
    private $priority = 3;
    private $retour = null;

    // mime_boundary
    private $semi_rand = null;
    private $mime_boundary = null;
    private $mime_boundary_header = null;

    // meassage & header
    private $message_html = null;
    private $message_text = null;
    private $header = null;
    private $message = null;


    function __construct($name, $email, $message, $subject, $html = false, $_retour = null, $_from = null, $_sender = null, $sitename = null)
    {

        $this->to_mail = $email; //empty ($name) ? "To: {$email}" : "To: \"{$_name}\" <{$email}>";
        $this->to_name = mb_encode_mimeheader($name);
        $this->retour = $_retour;
        $this->from = $_from;
        $this->sender = $_sender;
        $this->sujet = '=?UTF-8?B?' . base64_encode(html_entity_decode($subject)) . '?=';
        $this->message_html = ($html) ? $message : null;
        $this->is_html = $html;
        $this->site_name = $sitename;

        // objet convert html to text
        $objetHtml = new Html2Text($message);
        $this->message_text = $objetHtml->get_text();

        $this->precedence = "junk";
        $this->semi_rand = md5(time());
        $this->mime_boundary = "--b_{$this->semi_rand}";
        $this->mime_boundary_header = chr(34) . $this->mime_boundary . chr(34);


    }


    function mail_send()
    {
        try {
            $this->build_head();

            // text body
            $this->message = "\r\n\r\n{$this->mime_boundary}\r\n";
            $this->message .= "Content-type: text/plain;charset=utf-8\r\n\r\n";
            $this->message .= utf8_decode($this->message_text);
            $this->message .= "\r\n\r\n{$this->mime_boundary}\r\n";

            // Html body
            if ($this->is_html) {
                $this->message .= "Content-type: text/html;charset=utf-8\r\n\r\n";
                $this->message .= $this->message_html;
                $this->message .= "\r\n\r\n{$this->mime_boundary}--";
            }
            
            return @mail($this->to_mail, $this->sujet, $this->message, $this->header, "-f{$this->from}");
        } catch (Exception $e) {
            return null;
        }
    }

    private function build_head()
    {
        $headers = "From: \"" . mb_encode_mimeheader($this->from) . "\" <" . $this->sender . ">\r\n";
        $headers .= "Return-Path: <{$this->retour}>\r\n";

        $headers .= "To: \"" . $this->to_name . "\" <" . $this->to_mail . ">\r\n";
        // $headers .= "Reply-To: =?UTF-8?B?" . base64_encode($this->from) . "?=<" . $this->sender . ">\r\n";
        $headers .= "X-DKIM: " . $this->site_name . "\r\n";
        $headers .= "X-Priority: {$this->priority}\r\n";
        $headers .= "X-Mailer: {$this->mailer_name} [version {$this->mailer_version}]\r\n";
        $headers .= "Errors-To: <" . $this->retour . ">\r\n";
        $headers .= "Precedence: {$this->precedence}\r\n";
        $headers .= "X-Auto-Response-Suppress: All\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/alternative;\r\n";
        $headers .= "     boundary=\"b_{$this->semi_rand}\" \r\n";

        $this->header = str_replace("\r\n", "\n", $headers);
    }
}

?>