<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SymfonyMailer\Dependency\External;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MailAttachmentTransfer;
use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailSenderTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use ReflectionClass;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\SymfonyMailer\Business\Renderer\RendererInterface;
use Spryker\Zed\SymfonyMailer\Business\Translator\TranslatorInterface;
use Spryker\Zed\SymfonyMailer\Dependency\External\SymfonyMailerToMailerInterface;
use Spryker\Zed\SymfonyMailer\Dependency\External\SymfonyMailerToSymfonyMailerAdapter;
use Spryker\Zed\SymfonyMailer\SymfonyMailerConfig;
use Symfony\Component\Mime\Email;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SymfonyMailer
 * @group Dependency
 * @group External
 * @group SymfonyMailerToSymfonyMailerAdapterTest
 * Add your own group annotations below this line
 */
class SymfonyMailerToSymfonyMailerAdapterTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SymfonyMailer\SymfonyMailerDependencyTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const SUBJECT = 'subject';

    /**
     * @var string
     */
    protected const SUBJECT_TRANSLATED_PARAMETER = 'SUBJECT_TRANSLATED_PARAMETER';

    /**
     * @var string
     */
    protected const SUBJECT_NAME_PATTERN = '%name%';

    /**
     * @var string
     */
    protected const FROM_EMAIL = 'from@email.com';

    /**
     * @var string
     */
    protected const FROM_NAME = 'fromName';

    /**
     * @var string
     */
    protected const TO_EMAIL = 'to@email.com';

    /**
     * @var string
     */
    protected const TO_NAME = 'toName';

    /**
     * @var string
     */
    protected const BCC_EMAIL = 'bcc@email.com';

    /**
     * @var string
     */
    protected const BCC_NAME = 'bccName';

    /**
     * @var string
     */
    protected const HTML_MAIL_CONTENT = 'html mail content';

    /**
     * @var string
     */
    protected const TEXT_MAIL_CONTENT = 'text mail content';

    /**
     * @var string
     */
    protected const MAIL_ATTACHMENT_URL = 'http://mail-attachment-url';

    /**
     * @var string
     */
    protected const MAIL_ATTACHMENT_MIME_TYPE = 'text/plain';

    /**
     * @var string
     */
    protected const EMAIL = 'email';

    /**
     * @return void
     */
    public function testSendAddsSubjectToEmail(): void
    {
        //Arrange
        $mailTransfer = $this->haveMailTransfer();
        $mailTransfer->setSubjectTranslationParameters([
            static::SUBJECT_NAME_PATTERN => static::SUBJECT_TRANSLATED_PARAMETER,
        ]);

        $translatorMock = $this->getTranslatorMock();
        $translatorMock->expects($this->once())
            ->method('translate')
            ->with($mailTransfer, static::SUBJECT, [
                static::SUBJECT_NAME_PATTERN => static::SUBJECT_TRANSLATED_PARAMETER,
            ])
            ->willReturn(static::SUBJECT);

        $symfonyMailerToSymfonyMailerAdapterMock = $this->getSymfonyMailerToSymfonyMailerAdapterMock($translatorMock);

        //Act
        $symfonyMailerToSymfonyMailerAdapterMock->send($mailTransfer);

        /** @var \Symfony\Component\Mime\Email $symfonyEmail */
        $symfonyEmail = $this->getReflectedEmailData($symfonyMailerToSymfonyMailerAdapterMock);

        //Assert
        $this->assertEquals(static::SUBJECT, $symfonyEmail->getSubject());
    }

    /**
     * @return void
     */
    public function testSendAddsSenderToEmail(): void
    {
        //Arrange
        $symfonyMailerToSymfonyMailerAdapterMock = $this->getSymfonyMailerToSymfonyMailerAdapterMock();

        //Act
        $symfonyMailerToSymfonyMailerAdapterMock->send($this->haveMailTransfer());

        /** @var \Symfony\Component\Mime\Email $symfonyEmail */
        $symfonyEmail = $this->getReflectedEmailData($symfonyMailerToSymfonyMailerAdapterMock);

        //Assert
        $this->assertEquals(static::FROM_EMAIL, $symfonyEmail->getFrom()[0]->getAddress());
        $this->assertEquals(static::FROM_NAME, $symfonyEmail->getFrom()[0]->getName());
    }

    /**
     * @return void
     */
    public function testSendAddsRecipientToEmail(): void
    {
        //Arrange
        $symfonyMailerToSymfonyMailerAdapterMock = $this->getSymfonyMailerToSymfonyMailerAdapterMock();

        //Act
        $symfonyMailerToSymfonyMailerAdapterMock->send($this->haveMailTransfer());

        /** @var \Symfony\Component\Mime\Email $symfonyEmail */
        $symfonyEmail = $this->getReflectedEmailData($symfonyMailerToSymfonyMailerAdapterMock);

        //Assert
        $this->assertEquals(static::TO_EMAIL, $symfonyEmail->getTo()[0]->getAddress());
        $this->assertEquals(static::TO_NAME, $symfonyEmail->getTo()[0]->getName());
    }

    /**
     * @dataProvider getBccsDataProvider
     *
     * @param array<\Generated\Shared\Transfer\MailRecipientTransfer> $bccMailRecipients
     *
     * @return void
     */
    public function testSendAddsRecipientBccToEmail(array $bccMailRecipients): void
    {
        //Arrange
        $mailTransfer = $this->haveMailTransfer();

        foreach ($bccMailRecipients as $mailRecipientTransfer) {
            $mailTransfer->addRecipientBcc($mailRecipientTransfer);
        }

        $symfonyMailerToSymfonyMailerAdapterMock = $this->getSymfonyMailerToSymfonyMailerAdapterMock();

        //Act
        $symfonyMailerToSymfonyMailerAdapterMock->send($mailTransfer);

        /** @var \Symfony\Component\Mime\Email $symfonyEmail */
        $symfonyEmail = $this->getReflectedEmailData($symfonyMailerToSymfonyMailerAdapterMock);

        //Assert
        $this->assertCount(count($bccMailRecipients), $symfonyEmail->getBcc());
        foreach ($bccMailRecipients as $key => $bccMailRecipient) {
            $this->assertEquals($bccMailRecipient->getEmail(), $symfonyEmail->getBcc()[$key]->getAddress());
            $this->assertEquals($bccMailRecipient->getName(), $symfonyEmail->getBcc()[$key]->getName());
        }
    }

    /**
     * @return array
     */
    public function getBccsDataProvider(): array
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
    public function testSendExpectsBccEmail(): void
    {
        //Arrange
        $mailTransfer = $this->haveMailTransfer()->addRecipientBcc(
            (new MailRecipientTransfer())->setName(static::BCC_NAME),
        );
        $symfonyMailerToSymfonyMailerAdapterMock = $this->getSymfonyMailerToSymfonyMailerAdapterMock();

        //Assert
        $this->expectException(RequiredTransferPropertyException::class);

        //Act
        $symfonyMailerToSymfonyMailerAdapterMock->send($mailTransfer);
    }

    /**
     * @return void
     */
    public function testSendAddsHtmlContentToEmail(): void
    {
        //Arrange
        $symfonyMailerToSymfonyMailerAdapterMock = $this->getSymfonyMailerToSymfonyMailerAdapterMock();

        //Act
        $symfonyMailerToSymfonyMailerAdapterMock->send($this->haveMailTransfer());

        /** @var \Symfony\Component\Mime\Email $symfonyEmail */
        $symfonyEmail = $this->getReflectedEmailData($symfonyMailerToSymfonyMailerAdapterMock);

        //Assert
        $this->assertEquals(static::HTML_MAIL_CONTENT, $symfonyEmail->getHtmlBody());
    }

    /**
     * @return void
     */
    public function testSendAddsTextContentToEmail(): void
    {
        //Arrange
        $symfonyMailerToSymfonyMailerAdapterMock = $this->getSymfonyMailerToSymfonyMailerAdapterMock();

        //Act
        $symfonyMailerToSymfonyMailerAdapterMock->send($this->haveMailTransfer());

        /** @var \Symfony\Component\Mime\Email $symfonyEmail */
        $symfonyEmail = $this->getReflectedEmailData($symfonyMailerToSymfonyMailerAdapterMock);

        //Assert
        $this->assertEquals(static::TEXT_MAIL_CONTENT, $symfonyEmail->getTextBody());
    }

    /**
     * @dataProvider getMailAddsAttachmentsDataProvider
     *
     * @param array<\Generated\Shared\Transfer\MailAttachmentTransfer> $mailAttachmentTransfers
     *
     * @return void
     */
    public function testSendAddsAttachments(array $mailAttachmentTransfers): void
    {
        //Arrange
        $mailTransfer = $this->haveMailTransfer();
        foreach ($mailAttachmentTransfers as $mailAttachmentTransfer) {
            $mailTransfer->addAttachment($mailAttachmentTransfer);
        }
        $symfonyMailerToSymfonyMailerAdapterMock = $this->getSymfonyMailerToSymfonyMailerAdapterMock();

        //Act
        $symfonyMailerToSymfonyMailerAdapterMock->send($mailTransfer);

        /** @var \Symfony\Component\Mime\Email $symfonyEmail */
        $symfonyEmail = $this->getReflectedEmailData($symfonyMailerToSymfonyMailerAdapterMock);

        //Assert
        $this->assertCount(count($mailAttachmentTransfers), $symfonyEmail->getAttachments());
        foreach ($mailAttachmentTransfers as $key => $mailAttachmentTransfer) {
            $this->assertEquals(
                $mailAttachmentTransfer->getAttachmentUrl() ?? file_get_contents($mailAttachmentTransfer->getFileName()),
                $symfonyEmail->getAttachments()[$key]->getBody(),
            );
        }
    }

    /**
     * @return array<mixed>
     */
    public function getMailAddsAttachmentsDataProvider(): array
    {
        return [
            [ // 0 Attachments
                [],
            ],
            [ // 1 Attachment
                [
                    (new MailAttachmentTransfer())
                        ->setAttachmentUrl(static::MAIL_ATTACHMENT_URL),
                ],
            ],
            [ // multiple Attachments
                [
                    (new MailAttachmentTransfer())
                        ->setAttachmentUrl(static::MAIL_ATTACHMENT_URL),
                ],
                [
                    (new MailAttachmentTransfer())
                        ->setAttachmentUrl(static::MAIL_ATTACHMENT_URL),
                ],
                [
                    (new MailAttachmentTransfer())
                        ->setAttachmentUrl(static::MAIL_ATTACHMENT_URL),
                ],
            ],
            [ // file Attachment
                [
                    (new MailAttachmentTransfer())
                    ->setFileName(codecept_data_dir('Fixtures/attachment.txt'))
                    ->setMimeType(static::MAIL_ATTACHMENT_MIME_TYPE),
                ],
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function haveMailTransfer(): MailTransfer
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
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SymfonyMailer\Business\Translator\TranslatorInterface|null $translatorMock
     *
     * @return \Spryker\Zed\SymfonyMailer\Dependency\External\SymfonyMailerToMailerInterface
     */
    protected function getSymfonyMailerToSymfonyMailerAdapterMock(
        ?TranslatorInterface $translatorMock = null
    ): SymfonyMailerToMailerInterface {
        return new SymfonyMailerToSymfonyMailerAdapter(
            $this->getRendererMock(),
            $translatorMock ?? $this->getTranslatorMock(),
            $this->getConfig(),
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SymfonyMailer\Business\Renderer\RendererInterface
     */
    protected function getRendererMock(): RendererInterface
    {
        return $this->getMockBuilder(RendererInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SymfonyMailer\Business\Translator\TranslatorInterface
     */
    protected function getTranslatorMock(): TranslatorInterface
    {
        return $this->getMockBuilder(TranslatorInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\SymfonyMailer\SymfonyMailerConfig
     */
    protected function getConfig(): SymfonyMailerConfig
    {
        return new SymfonyMailerConfig();
    }

    /**
     * @param \Spryker\Zed\SymfonyMailer\Dependency\External\SymfonyMailerToMailerInterface $symfonyMailerToSymfonyMailerAdapterMock
     *
     * @return \Symfony\Component\Mime\Email
     */
    protected function getReflectedEmailData(SymfonyMailerToMailerInterface $symfonyMailerToSymfonyMailerAdapterMock): Email
    {
        $reflectedAdapterMock = new ReflectionClass($symfonyMailerToSymfonyMailerAdapterMock);
        $emailProperty = $reflectedAdapterMock->getProperty(static::EMAIL);
        $emailProperty->setAccessible(true);

        return $emailProperty->getValue($symfonyMailerToSymfonyMailerAdapterMock);
    }
}
