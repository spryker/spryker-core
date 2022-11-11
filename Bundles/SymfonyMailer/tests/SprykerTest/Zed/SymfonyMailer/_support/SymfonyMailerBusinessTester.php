<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SymfonyMailer;

use Codeception\Actor;
use Spryker\Zed\SymfonyMailer\Dependency\Facade\SymfonyMailerToLocaleFacadeBridge;
use Spryker\Zed\SymfonyMailer\Dependency\Facade\SymfonyMailerToLocaleFacadeInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class SymfonyMailerBusinessTester extends Actor
{
    use _generated\SymfonyMailerBusinessTesterActions;

    /**
     * @return \Spryker\Zed\SymfonyMailer\Dependency\Facade\SymfonyMailerToLocaleFacadeInterface
     */
    public function getLocaleFacade(): SymfonyMailerToLocaleFacadeInterface
    {
        return new SymfonyMailerToLocaleFacadeBridge($this->getLocator()->locale()->facade());
    }

    /**
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function haveMailTransfer(): MailTransfer
    {
        $mailTransfer = new MailTransfer();
        $mailTransfer->setSubject(static::SUBJECT);

        $mailSenderTransfer = new MailSenderTransfer();
        $mailSenderTransfer
            ->setEmail(static::FROM_EMAIL)
            ->setName(static::FROM_NAME);

        $mailTransfer->setSender($mailSenderTransfer);

        $mailRecipientTransfer = new MailRecipientTransfer();
        $mailRecipientTransfer
            ->setEmail(static::TO_EMAIL)
            ->setName(static::TO_NAME);

        $mailTransfer->addRecipient($mailRecipientTransfer);

        $mailHtmlTemplate = new MailTemplateTransfer();
        $mailHtmlTemplate
            ->setIsHtml(true)
            ->setContent(static::HTML_MAIL_CONTENT);

        $mailTransfer->addTemplate($mailHtmlTemplate);

        $mailTextTemplate = new MailTemplateTransfer();
        $mailTextTemplate
            ->setIsHtml(false)
            ->setContent(static::TEXT_MAIL_CONTENT);

        $mailTransfer->addTemplate($mailTextTemplate);

        return $mailTransfer;
    }
}
