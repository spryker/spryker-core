<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Mail\Business\Model\Provider;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailSenderTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Mail\Business\Model\Provider\SwiftMailer;
use Spryker\Zed\Mail\Business\Model\Renderer\RendererInterface;
use Spryker\Zed\Mail\Dependency\Mailer\MailToMailerInterface;
use Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Mail
 * @group Business
 * @group Model
 * @group Provider
 * @group SwiftMailerTest
 * Add your own group annotations below this line
 */
class SwiftMailerTest extends Unit
{
    public const SUBJECT = 'subject';
    public const FROM_EMAIL = 'from@email.com';
    public const FROM_NAME = 'fromName';
    public const TO_EMAIL = 'to@email.com';
    public const TO_NAME = 'toName';
    public const HTML_MAIL_CONTENT = 'html mail content';
    public const TEXT_MAIL_CONTENT = 'text mail content';

    /**
     * @return void
     */
    public function testInstantiation()
    {
        $rendererMock = $this->getRendererMock();
        $mailerMock = $this->getMailerMock();
        $swiftMailer = new SwiftMailer($rendererMock, $mailerMock);

        $this->assertInstanceOf(MailProviderPluginInterface::class, $swiftMailer);
    }

    /**
     * @return void
     */
    public function testSendMailAddSubjectToMessage()
    {
        $mailerMock = $this->getMailerMock();
        $mailerMock->expects($this->once())->method('setSubject')->with(static::SUBJECT);

        $swiftMailer = $this->getSwiftMailerWithMocks($mailerMock);
        $swiftMailer->sendMail($this->getMailTransfer());
    }

    /**
     * @return void
     */
    public function testSendMailAddsSenderToMessage()
    {
        $mailerMock = $this->getMailerMock();
        $mailerMock->expects($this->once())->method('setFrom')->with(static::FROM_EMAIL, static::FROM_NAME);

        $swiftMailer = $this->getSwiftMailerWithMocks($mailerMock);
        $swiftMailer->sendMail($this->getMailTransfer());
    }

    /**
     * @return void
     */
    public function testSendMailAddRecipientToMessage()
    {
        $mailerMock = $this->getMailerMock();
        $mailerMock->expects($this->once())->method('addTo')->with(static::TO_EMAIL, static::TO_NAME);

        $swiftMailer = $this->getSwiftMailerWithMocks($mailerMock);
        $swiftMailer->sendMail($this->getMailTransfer());
    }

    /**
     * @return void
     */
    public function testSendMailAddHtmlContentToMessage()
    {
        $mailerMock = $this->getMailerMock();
        $mailerMock->expects($this->once())->method('setHtmlContent')->with(static::HTML_MAIL_CONTENT);

        $swiftMailer = $this->getSwiftMailerWithMocks($mailerMock);
        $swiftMailer->sendMail($this->getMailTransfer());
    }

    /**
     * @return void
     */
    public function testSendMailAddTextContentToMessage()
    {
        $mailerMock = $this->getMailerMock();
        $mailerMock->expects($this->once())->method('setTextContent')->with(static::TEXT_MAIL_CONTENT);

        $swiftMailer = $this->getSwiftMailerWithMocks($mailerMock);
        $swiftMailer->sendMail($this->getMailTransfer());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Business\Model\Renderer\RendererInterface
     */
    protected function getRendererMock()
    {
        $rendererMock = $this->getMockBuilder(RendererInterface::class)->getMock();

        return $rendererMock;
    }

    /**
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function getMailTransfer()
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

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Dependency\Mailer\MailToMailerInterface
     */
    protected function getMailerMock()
    {
        $mailerMock = $this->getMockBuilder(MailToMailerInterface::class)
            ->setMethods(['setSubject', 'setFrom', 'addTo', 'setHtmlContent', 'setTextContent', 'send'])
            ->getMock();

        return $mailerMock;
    }

    /**
     * @param \Spryker\Zed\Mail\Dependency\Mailer\MailToMailerInterface $mailerMock
     *
     * @return \Spryker\Zed\Mail\Business\Model\Provider\SwiftMailer
     */
    protected function getSwiftMailerWithMocks(MailToMailerInterface $mailerMock)
    {
        $renderMock = $this->getRendererMock();
        $swiftMailer = new SwiftMailer($renderMock, $mailerMock);

        return $swiftMailer;
    }
}
