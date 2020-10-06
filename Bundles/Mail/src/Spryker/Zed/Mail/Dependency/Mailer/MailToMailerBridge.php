<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Dependency\Mailer;

use Swift_Attachment;

class MailToMailerBridge implements MailToMailerInterface
{
    /**
     * @var \Swift_Message
     */
    protected $message;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @param \Swift_Message $message
     * @param \Swift_Mailer $mailer
     */
    public function __construct($message, $mailer)
    {
        $this->message = $message;
        $this->mailer = $mailer;
    }

    /**
     * @param string $subject
     *
     * @return void
     */
    public function setSubject($subject)
    {
        $this->message->setSubject($subject);
    }

    /**
     * @param string $email
     * @param string|null $name
     *
     * @return void
     */
    public function setFrom($email, $name = null)
    {
        $this->message->setFrom($email, $name);
    }

    /**
     * @param string $email
     * @param string|null $name
     *
     * @return void
     */
    public function addTo($email, $name = null)
    {
        $this->message->addTo($email, $name);
    }

    /**
     * @param string $email
     * @param string|null $name
     *
     * @return void
     */
    public function addBcc(string $email, ?string $name = null): void
    {
        $this->message->addBcc($email, $name);
    }

    /**
     * @param string $content
     *
     * @return void
     */
    public function setHtmlContent($content)
    {
        $this->message->setBody($content, 'text/html');
    }

    /**
     * @param string $content
     *
     * @return void
     */
    public function setTextContent($content)
    {
        $this->message->addPart($content, 'text/plain');
    }

    /**
     * @return void
     */
    public function send()
    {
        $this->mailer->send($this->message);
    }

    /**
     * @param string $attachment
     *
     * @return void
     */
    public function addAttachment(string $attachment): void
    {
        $this->message->attach(Swift_Attachment::fromPath($attachment));
    }
}
