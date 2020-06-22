<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Provider;

use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Mail\Business\Model\Renderer\RendererInterface;
use Spryker\Zed\Mail\Dependency\Mailer\MailToMailerInterface;
use Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface;

class SwiftMailer implements MailProviderPluginInterface
{
    /**
     * @var \Spryker\Zed\Mail\Business\Model\Renderer\RendererInterface
     */
    protected $renderer;

    /**
     * @var \Spryker\Zed\Mail\Dependency\Mailer\MailToMailerInterface
     */
    protected $mailer;

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Renderer\RendererInterface $renderer
     * @param \Spryker\Zed\Mail\Dependency\Mailer\MailToMailerInterface $mailer
     */
    public function __construct(RendererInterface $renderer, MailToMailerInterface $mailer)
    {
        $this->renderer = $renderer;
        $this->mailer = $mailer;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        $this
            ->addSubject($mailTransfer)
            ->addFrom($mailTransfer)
            ->addTo($mailTransfer)
            ->addBcc($mailTransfer)
            ->addContent($mailTransfer);

        $this->mailer->send();
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return $this
     */
    protected function addSubject(MailTransfer $mailTransfer)
    {
        $this->mailer->setSubject($mailTransfer->getSubject());

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return $this
     */
    protected function addFrom(MailTransfer $mailTransfer)
    {
        $senderTransfer = $mailTransfer->requireSender()->getSender();

        $this->mailer->setFrom($senderTransfer->getEmail(), $senderTransfer->getName());

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return $this
     */
    protected function addTo(MailTransfer $mailTransfer)
    {
        $recipientTransferCollection = $mailTransfer->requireRecipients()->getRecipients();

        foreach ($recipientTransferCollection as $recipientTransfer) {
            $this->mailer->addTo($recipientTransfer->getEmail(), $recipientTransfer->getName());
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return $this
     */
    protected function addBcc(MailTransfer $mailTransfer)
    {
        $mailRecipientTransfers = $mailTransfer->getRecipientBccs();
        foreach ($mailRecipientTransfers as $mailRecipientTransfer) {
            $mailRecipientTransfer->requireEmail();

            $this->mailer->addBcc($mailRecipientTransfer->getEmail(), $mailRecipientTransfer->getName());
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return $this
     */
    protected function addContent(MailTransfer $mailTransfer)
    {
        $this->renderer->render($mailTransfer);

        foreach ($mailTransfer->requireTemplates()->getTemplates() as $templateTransfer) {
            if ($templateTransfer->getIsHtml()) {
                $this->mailer->setHtmlContent($templateTransfer->getContent());
            }

            if (!$templateTransfer->getIsHtml()) {
                $this->mailer->setTextContent($templateTransfer->getContent());
            }
        }

        return $this;
    }
}
