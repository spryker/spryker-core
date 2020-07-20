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
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
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
    protected const SUBJECT = 'subject';
    protected const FROM_EMAIL = 'from@email.com';
    protected const FROM_NAME = 'fromName';
    protected const TO_EMAIL = 'to@email.com';
    protected const TO_NAME = 'toName';
    protected const BCC_EMAIL = 'bcc@email.com';
    protected const BCC_NAME = 'bccName';
    protected const HTML_MAIL_CONTENT = 'html mail content';
    protected const TEXT_MAIL_CONTENT = 'text mail content';

    /**
     * @return void
     */
    public function testInstantiation(): void
    {
        $rendererMock = $this->getRendererMock();
        $mailerMock = $this->getMailerMock();
        $swiftMailer = new SwiftMailer($rendererMock, $mailerMock);

        $this->assertInstanceOf(MailProviderPluginInterface::class, $swiftMailer);
    }

    /**
     * @return void
     */
    public function testSendMailAddSubjectToMessage(): void
    {
        $mailerMock = $this->getMailerMock();
        $mailerMock->expects($this->once())->method('setSubject')->with(static::SUBJECT);

        $swiftMailer = $this->getSwiftMailerWithMocks($mailerMock);
        $swiftMailer->sendMail($this->getMailTransfer());
    }

    /**
     * @return void
     */
    public function testSendMailAddsSenderToMessage(): void
    {
        $mailerMock = $this->getMailerMock();
        $mailerMock->expects($this->once())->method('setFrom')->with(static::FROM_EMAIL, static::FROM_NAME);

        $swiftMailer = $this->getSwiftMailerWithMocks($mailerMock);
        $swiftMailer->sendMail($this->getMailTransfer());
    }

    /**
     * @return void
     */
    public function testSendMailAddRecipientToMessage(): void
    {
        $mailerMock = $this->getMailerMock();
        $mailerMock->expects($this->once())->method('addTo')->with(static::TO_EMAIL, static::TO_NAME);

        $swiftMailer = $this->getSwiftMailerWithMocks($mailerMock);
        $swiftMailer->sendMail($this->getMailTransfer());
    }

    /**
     * @uses MailToMailerInterface::addBcc()
     *
     * @dataProvider provideBccs
     *
     * @param \Generated\Shared\Transfer\MailRecipientTransfer[] $bccMailRecipients
     *
     * @return void
     */
    public function testSendMailAddsRecipientBccToMessage(array $bccMailRecipients): void
    {
        // Assign
        $mailerMock = $this->getMailerMock();
        $swiftMailer = $this->getSwiftMailerWithMocks($mailerMock);
        $mailTransfer = $this->getMailTransfer();
        foreach ($bccMailRecipients as $mailRecipientTransfer) {
            $mailTransfer->addRecipientBcc($mailRecipientTransfer);
        }

        // Assert
        $mailerMock->expects($this->exactly(count($bccMailRecipients)))->method('addBcc');

        // Act
        $swiftMailer->sendMail($mailTransfer);
    }

    /**
     * @return array
     */
    public function provideBccs(): array
    {
        return [
            [ // 0 BCCs
                [],
            ],
            [ // 1 BCC
                [
                    (new MailRecipientTransfer())
                        ->setEmail(static::BCC_EMAIL)
                        ->setName(static::BCC_NAME),
                ],
            ],
            [ // multiple BCCs
                [
                    (new MailRecipientTransfer())
                        ->setEmail(static::BCC_EMAIL)
                        ->setName(static::BCC_NAME),
                ],
                [
                    (new MailRecipientTransfer())
                        ->setEmail(static::BCC_EMAIL)
                        ->setName(static::BCC_NAME),
                ],
                [
                    (new MailRecipientTransfer())
                        ->setEmail(static::BCC_EMAIL)
                        ->setName(static::BCC_NAME),
                ],
            ],
        ];
    }

    /**
     * @return void
     */
    public function testSendMailExpectsBccEmail(): void
    {
        // Assign
        $mailerMock = $this->getMailerMock();
        $swiftMailer = $this->getSwiftMailerWithMocks($mailerMock);
        $mailTransfer = $this->getMailTransfer()->addRecipientBcc(
            (new MailRecipientTransfer())
                ->setName(static::BCC_NAME)
        );

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $swiftMailer->sendMail($mailTransfer);
    }

    /**
     * @return void
     */
    public function testSendMailAddHtmlContentToMessage(): void
    {
        $mailerMock = $this->getMailerMock();
        $mailerMock->expects($this->once())->method('setHtmlContent')->with(static::HTML_MAIL_CONTENT);

        $swiftMailer = $this->getSwiftMailerWithMocks($mailerMock);
        $swiftMailer->sendMail($this->getMailTransfer());
    }

    /**
     * @return void
     */
    public function testSendMailAddTextContentToMessage(): void
    {
        $mailerMock = $this->getMailerMock();
        $mailerMock->expects($this->once())->method('setTextContent')->with(static::TEXT_MAIL_CONTENT);

        $swiftMailer = $this->getSwiftMailerWithMocks($mailerMock);
        $swiftMailer->sendMail($this->getMailTransfer());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Business\Model\Renderer\RendererInterface
     */
    protected function getRendererMock(): RendererInterface
    {
        $rendererMock = $this->getMockBuilder(RendererInterface::class)->getMock();

        return $rendererMock;
    }

    /**
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function getMailTransfer(): MailTransfer
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
     * @uses MailToMailerInterface::setSubject()
     * @uses MailToMailerInterface::setFrom()
     * @uses MailToMailerInterface::addTo()
     * @uses MailToMailerInterface::addBcc()
     * @uses MailToMailerInterface::setHtmlContent()
     * @uses MailToMailerInterface::setTextContent()
     * @uses MailToMailerInterface::send()
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Dependency\Mailer\MailToMailerInterface
     */
    protected function getMailerMock(): MailToMailerInterface
    {
        $mailerMock = $this->getMockBuilder(MailToMailerInterface::class)
            ->setMethods(['setSubject', 'setFrom', 'addTo', 'addBcc', 'setHtmlContent', 'setTextContent', 'send'])
            ->getMock();

        return $mailerMock;
    }

    /**
     * @param \Spryker\Zed\Mail\Dependency\Mailer\MailToMailerInterface $mailerMock
     *
     * @return \Spryker\Zed\Mail\Business\Model\Provider\SwiftMailer
     */
    protected function getSwiftMailerWithMocks(MailToMailerInterface $mailerMock): SwiftMailer
    {
        $renderMock = $this->getRendererMock();
        $swiftMailer = new SwiftMailer($renderMock, $mailerMock);

        return $swiftMailer;
    }
}
