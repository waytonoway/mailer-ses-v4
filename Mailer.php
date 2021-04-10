<?php
/**
 *
 * Mailer implements a mailer based on Amazon SES.
 *
 * To use Mailer, you should configure it in the application configuration like the following,
 *
 * ~~~
 * 'components' => [
 *     ...
 *     'mail' => [
 *         'class' => 'waytonoway\ses\Mailer',
 *         'access_key' => 'Your access key',
 *         'secret_key' => 'Your secret key'
 *     ],
 *     ...
 * ],
 * ~~~
 *
 * To send an email, you may use the following code:
 *
 * ~~~
 * Yii::$app->mail->compose('contact/html', ['contactForm' => $form])
 *     ->setFrom('from@domain.com')
 *     ->setTo($form->email)
 *     ->setSubject($form->subject)
 *     ->send();
 * ~~~
 */

namespace waytonoway\ses;

use Yii;
use yii\mail\BaseMailer;
use yii\mail\MessageInterface;

class Mailer extends BaseMailer
{
    /**
     * @var string message default class name.
     */
    public $messageClass = 'waytonoway\ses\Message';

    /**
     * @var string Amazon ses api access key
     */
    public $accessKey;

    /**
     * @var string Amazon ses api secret key
     */
    public $secretKey;

    /**
     * @var string A default from address to send email
     */
    public $defaultFrom;

    /**
     * @var string Amazon ses host
     */
    public $host = 'email.us-east-1.amazonaws.com';

    /**
     * @var \SimpleEmailService SimpleEmailService instance.
     */
    private $_ses;

    /**
     * @return \SimpleEmailService SimpleEmailService instance.
     */
    public function getSES()
    {
        if (!is_object($this->_ses)) {
            $this->_ses = new \SimpleEmailService($this->accessKey, $this->secretKey, $this->host);
        }

        return $this->_ses;
    }

    protected function sendMessage($message)
    {
        if ( is_null($message->getFrom()) && isset($this->defaultFrom)) {
            if(!is_array($this->defaultFrom)){
                $this->defaultFrom = array($this->defaultFrom => $this->defaultFrom);
            }

            $message->setFrom($this->defaultFrom);
        };

        $message->setDate(time());
        return $this->getSES()->sendEmail($message->getSesMessage());
    }
}
