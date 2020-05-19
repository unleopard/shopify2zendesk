<?php
/**
 * Description of SMTP Sender
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: SMTPSender
 */

require_once ROOT . DS . LIB_DIR . DS . "composer" . DS . "vendor" . DS . "phpmailer" . DS . "phpmailer" . DS . "src" . DS . "PHPMailer.php";

class SMTPSender extends \PHPMailer\PHPMailer\PHPMailer
{

    public function __construct()
    {
        parent::__construct(true);

        $this->isSMTP();
//        $this->SMTPDebug = SMTP_DEBUG;

        $this->Host = SMTP_HOST;
        $this->Port = SMTP_PORT;
        $this->Username = SMTP_USER;
        $this->Password = SMTP_PASS;
        $this->SMTPAuth = true;
        $this->SMTPSecure = 'tls';

    }

    public function sendMail($receiver, $subject, $message)
    {
        $sender_mail = SMTP_USER;
        $receiver_mail = (is_array($receiver)) ? $receiver['mail'] : $receiver;

        $sender_name = SMTP_SENDER_NAME;
        $receiver_name = (is_array($receiver)) ? $receiver['name'] : "";

        $this->setFrom($sender_mail, $sender_name);
        $this->addAddress($receiver_mail, $receiver_name);

        $this->Subject = $subject;
        $this->msgHTML($message);

        $this->AltBody = 'To view the message, please use an HTML compatible email viewer!';
        $this->action_function = 'callbackAction';

        return $this->send();
    }

    public function addfiles($file, $name = null)
    {
        if(is_null($name))
            $this->addAttachment($file);
        else
            $this->addAttachment($file, $name);
    }
}

