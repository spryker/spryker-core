<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Mail\Builder;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailSenderTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Mail\Business\Exception\MissingMailTransferException;
use Spryker\Zed\Mail\Dependency\Facade\MailToGlossaryInterface;

class MailBuilder implements MailBuilderInterface
{
    /**
     * @var \Generated\Shared\Transfer\MailTransfer|null
     */
    protected $mailTransfer;

    /**
     * @var \Spryker\Zed\Mail\Dependency\Facade\MailToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\Mail\Dependency\Facade\MailToGlossaryInterface $glossaryFacade
     */
    public function __construct(MailToGlossaryInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return $this
     */
    public function setMailTransfer(MailTransfer $mailTransfer)
    {
        $this->mailTransfer = $mailTransfer;

        return $this;
    }

    /**
     * @throws \Spryker\Zed\Mail\Business\Exception\MissingMailTransferException
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function getMailTransfer()
    {
        if (!$this->mailTransfer) {
            throw new MissingMailTransferException(sprintf('No MailTransfer set in "%s".', static::class));
        }

        return $this->mailTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer()
    {
        $localeTransfer = $this->getMailTransfer()->getLocale();

        if (!$localeTransfer) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->setLocaleName(Store::getInstance()->getCurrentLocale());
        }

        return $localeTransfer;
    }

    /**
     * @param string $subject
     * @param array $data
     *
     * @return $this
     */
    public function setSubject($subject, array $data = [])
    {
        $subject = $this->translate($subject, $data);

        $this->getMailTransfer()->setSubject($subject);

        return $this;
    }

    /**
     * @param string $htmlTemplate
     *
     * @return $this
     */
    public function setHtmlTemplate($htmlTemplate)
    {
        $this->addTemplate($htmlTemplate, true);

        return $this;
    }

    /**
     * @param string $textTemplate
     *
     * @return $this
     */
    public function setTextTemplate($textTemplate)
    {
        $this->addTemplate($textTemplate, false);

        return $this;
    }

    /**
     * @param string $templatePath
     * @param bool $isHtml
     *
     * @return void
     */
    protected function addTemplate($templatePath, $isHtml)
    {
        $mailTemplateTransfer = new MailTemplateTransfer();
        $mailTemplateTransfer
            ->setIsHtml($isHtml)
            ->setName($templatePath);

        $this->getMailTransfer()->addTemplate($mailTemplateTransfer);
    }

    /**
     * @param string $email
     * @param string $name
     *
     * @return $this
     */
    public function setSender($email, $name)
    {
        $mailSenderTransfer = new MailSenderTransfer();
        $mailSenderTransfer
            ->setEmail($this->translate($email))
            ->setName($this->translate($name));

        $this->getMailTransfer()->setSender($mailSenderTransfer);

        return $this;
    }

    /**
     * @param string $email
     * @param string $name
     *
     * @return $this
     */
    public function addRecipient($email, $name)
    {
        $mailRecipientTransfer = new MailRecipientTransfer();
        $mailRecipientTransfer
            ->setEmail($email)
            ->setName($name);

        $this->getMailTransfer()->addRecipient($mailRecipientTransfer);

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function build()
    {
        return $this->getMailTransfer();
    }

    /**
     * @param string $keyName
     * @param array $data
     *
     * @return string
     */
    protected function translate($keyName, array $data = [])
    {
        $localeTransfer = $this->getLocaleTransfer();

        if ($this->glossaryFacade->hasTranslation($keyName, $localeTransfer)) {
            $keyName = $this->glossaryFacade->translate($keyName, $data, $localeTransfer);
        }

        return $keyName;
    }
}
