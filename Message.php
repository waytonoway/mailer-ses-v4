<?php
namespace waytonoway\ses;

use yii\mail\BaseMessage;

class Message extends BaseMessage
{
    /**
     * @var \SimpleEmailServiceMessage Simple Email Service message instance.
     */
    private $_sesMessage;

    /**
     * @var string Text content
     */
    private $messageText;

    /**
     * @var string Html content
     */
    private $messageHtml = null;

    /**
     * @var string Message charset
     */
    private $charset;

    /**
     * @var string Message sender
     */
    private $from;

    /**
     * @var string replyTo
     */
    private $replyTo;

    /**
     * @var string To
     */
    private $to;

    /**
     * @var string CC
     */
    private $cc;

    /**
     * @var string BCC
     */
    private $bcc;

    /**
     * @var string Subject
     */
    private $subject;

    /**
     * @var integer Sending time for debugging
     */
    private $time;


    public function getSwiftMessage(): Message
    {
        return $this;
    }

    public function getSesMessage(): \SimpleEmailServiceMessage
    {
        if (!is_object($this->_sesMessage)) {
            $this->_sesMessage = new \SimpleEmailServiceMessage();
        }

        return $this->_sesMessage;
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function setCharset($charset)
    {
        $this->getSesMessage()->setMessageCharset($charset);
        $this->getSesMessage()->setSubjectCharset($charset);

        $this->charset = $charset;

        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from, $name = null)
    {
        if (!isset($name)) {
            $name = gethostname();
        }
        if (!is_array($from) && isset($name)) {
            $from = array($from => $name);
        }
        list($address) = array_keys($from);
        $name = $from[$address];
        $this->from = $name.' <'.$address.'>';
        $this->getSesMessage()->setFrom($this->from);

        return $this;
    }

    public function getReplyTo()
    {
        return $this->replyTo;
    }

    public function setReplyTo($replyTo)
    {
        $this->getSesMessage()->addReplyTo($replyTo);
        $this->replyTo = $replyTo;

        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to)
    {
        $this->getSesMessage()->addTo($to);
        $this->to = $to;

        return $this;
    }

    public function getCc()
    {
        return $this->cc;
    }

    public function setCc($cc)
    {
        $this->getSesMessage()->addCC($cc);
        $this->cc = $cc;

        return $this;
    }

    public function getBcc()
    {
        return $this->bcc;
    }

    public function setBcc($bcc)
    {
        $this->getSesMessage()->addBCC($bcc);
        $this->bcc = $bcc;

        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->getSesMessage()->setSubject($subject);

        return $this;
    }

    public function setTextBody($text)
    {
        $this->messageText = $text;
        $this->setBody($this->messageText, $this->messageHtml);

        return $this;
    }

    public function setHtmlBody($html)
    {
        $this->messageHtml = $html;
        $this->setBody($this->messageText, $this->messageHtml);

        return $this;
    }

    public function getBody()
    {
        return $this->messageText;
    }

    public function setBody($text, $html = null)
    {
        $this->getSesMessage()->setMessageFromString($text, $html);
    }

    public function attach($fileName, array $options = [])
    {
        $name = $fileName;
        $mimeType = 'application/octet-stream';

        if (!empty($options['fileName'])) {
            $name = $options['fileName'];
        }
        if (!empty($options['contentType'])) {
            $mimeType = $options['contentType'];
        }
        $this->getSesMessage()->addAttachmentFromFile($name, $fileName, $mimeType);

        return $this;
    }

    public function attachContent($content, array $options = [])
    {
        $name = 'file 1';
        $mimeType = 'application/octet-stream';

        if (!empty($options['fileName'])) {
            $name = $options['fileName'];
        }
        if (!empty($options['contentType'])) {
            $mimeType = $options['contentType'];
        }
        $this->getSesMessage()->addAttachmentFromData($name, $content, $mimeType);

        return $this;
    }

    public function embed($fileName, array $options = [])
    {
        return $this->attach($fileName, $options);
    }

    public function embedContent($content, array $options = [])
    {
        return $this->attachContent($content, $options);
    }

    public function toString()
    {
        return $this->getSesMessage()->getRawMessage();
    }

    public function setDate($time)
    {
        $this->time = $time;

        return $this;
    }

    public function getDate()
    {
        return $this->time;
    }
}
