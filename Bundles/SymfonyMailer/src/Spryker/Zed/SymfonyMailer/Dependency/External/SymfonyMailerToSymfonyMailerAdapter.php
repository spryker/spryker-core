<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SymfonyMailer\Dependency\External;

use Generated\Shared\Transfer\MailAttachmentTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\SymfonyMailer\Business\Renderer\RendererInterface;
use Spryker\Zed\SymfonyMailer\Business\Translator\TranslatorInterface;
use Spryker\Zed\SymfonyMailer\SymfonyMailerConfig;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class SymfonyMailerToSymfonyMailerAdapter implements SymfonyMailerToMailerInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SENDER_EMAIL = 'mail.sender.email';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SENDER_NAME = 'mail.sender.name';

    /**
     * @var \Spryker\Zed\SymfonyMailer\Business\Renderer\RendererInterface
     */
    protected RendererInterface $renderer;

    /**
     * @var \Spryker\Zed\SymfonyMailer\Business\Translator\TranslatorInterface
     */
    protected TranslatorInterface $translator;

    /**
     * @var \Symfony\Component\Mailer\MailerInterface
     */
    protected MailerInterface $mailer;

    /**
     * @var \Symfony\Component\Mime\Email
     */
    protected Email $email;

    /**
     * @var \Spryker\Zed\SymfonyMailer\SymfonyMailerConfig
     */
    protected SymfonyMailerConfig $config;

    /**
     * @param \Spryker\Zed\SymfonyMailer\Business\Renderer\RendererInterface $renderer
     * @param \Spryker\Zed\SymfonyMailer\Business\Translator\TranslatorInterface $translator
     * @param \Spryker\Zed\SymfonyMailer\SymfonyMailerConfig $config
     */
    public function __construct(
        RendererInterface $renderer,
        TranslatorInterface $translator,
        SymfonyMailerConfig $config
    ) {
        $this->renderer = $renderer;
        $this->translator = $translator;
        $this->config = $config;
        $this->email = new Email();
        $this->mailer = new Mailer($this->createEsmtpTransport());
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function send(MailTransfer $mailTransfer): void
    {
        $this->renderer->render($mailTransfer);

        $this
            ->addSubject($mailTransfer)
            ->addFrom($mailTransfer)
            ->addTo($mailTransfer)
            ->addBcc($mailTransfer)
            ->addContent($mailTransfer)
            ->addPriority($mailTransfer)
            ->addAttachments($mailTransfer);

        $this->mailer->send($this->email);
    }

    /**
     * @return \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport
     */
    protected function createEsmtpTransport(): EsmtpTransport
    {
        return (new EsmtpTransport(
            $this->config->getSmtpHost(),
            $this->config->getSmtpPort(),
            $this->config->isSmtpEncrypted(),
        ))
            ->setPassword($this->config->getSmtpPassword())
            ->setUsername($this->config->getSmtpUsername());
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
            $this->email->addTo(new Address(
                $recipientTransfer->getEmailOrFail(),
                $recipientTransfer->getName() ?? '',
            ));
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return $this
     */
    protected function addSubject(MailTransfer $mailTransfer)
    {
        $this->email->subject(
            $this->translator->translate(
                $mailTransfer,
                $mailTransfer->getSubjectOrFail(),
                $mailTransfer->getSubjectTranslationParameters(),
            ),
        );

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return $this
     */
    protected function addPriority(MailTransfer $mailTransfer)
    {
        if ($mailTransfer->getPriority() !== null) {
            $this->email->priority($mailTransfer->getPriority());

            return $this;
        }

        $this->email->priority(Email::PRIORITY_NORMAL);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return $this
     */
    protected function addFrom(MailTransfer $mailTransfer)
    {
        $senderTransfer = $mailTransfer->getSenderOrFail();
        $senderEmail = $senderTransfer->getEmail() ?: $this->translator->translate($mailTransfer, static::GLOSSARY_KEY_SENDER_EMAIL);
        $this->email->from(new Address(
            $senderEmail,
            $senderTransfer->getName() ?: $this->translator->translate($mailTransfer, static::GLOSSARY_KEY_SENDER_NAME),
        ));

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

            $this->email->addBcc(new Address(
                $mailRecipientTransfer->getEmailOrFail(),
                $mailRecipientTransfer->getName() ?? '',
            ));
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
        foreach ($mailTransfer->requireTemplates()->getTemplates() as $templateTransfer) {
            if ($templateTransfer->getIsHtml()) {
                $this->email->html($templateTransfer->getContent());

                continue;
            }

            $this->email->text($templateTransfer->getContent() ?? '');
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    protected function addAttachments(MailTransfer $mailTransfer): void
    {
        foreach ($mailTransfer->getAttachments() as $mailAttachmentTransfer) {
            if ($mailAttachmentTransfer->getFileName()) {
                $this->processLocalFile($mailAttachmentTransfer);

                continue;
            }

            $this->email->attach($mailAttachmentTransfer->getAttachmentUrlOrFail());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MailAttachmentTransfer $mailAttachmentTransfer
     *
     * @return void
     */
    protected function processLocalFile(MailAttachmentTransfer $mailAttachmentTransfer): void
    {
        $resource = fopen($mailAttachmentTransfer->getFileNameOrFail(), 'r');
        if ($resource === false) {
            return;
        }

        $content = stream_get_contents($resource);
        fclose($resource);

        if ($content === false) {
            return;
        }

        $this->email->attach($content, basename($mailAttachmentTransfer->getFileNameOrFail()), $mailAttachmentTransfer->getMimeType());
    }
}
