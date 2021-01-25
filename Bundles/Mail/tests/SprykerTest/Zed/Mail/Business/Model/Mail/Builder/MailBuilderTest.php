<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Mail\Business\Model\Mail\Builder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailSenderTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Mail\Business\Exception\MissingMailTransferException;
use Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilder;
use Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface;
use Spryker\Zed\Mail\Dependency\Facade\MailToGlossaryInterface;
use Spryker\Zed\Mail\MailConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Mail
 * @group Business
 * @group Model
 * @group Mail
 * @group Builder
 * @group MailBuilderTest
 * Add your own group annotations below this line
 */
class MailBuilderTest extends Unit
{
    protected const SUBJECT = 'subject';
    protected const TEMPLATE_NAME_HTML = 'html.template.name';
    protected const TEMPLATE_NAME_TEXT = 'text.template.name';
    protected const EMAIL = 'email';
    protected const NAME = 'name';
    protected const BCC_EMAIL = 'bcc@email.com';
    protected const BCC_NAME = 'bccName';

    /**
     * @return void
     */
    public function testInstantiation(): void
    {
        $mailBuilder = $this->getMailBuilder();

        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder);
    }

    /**
     * @return void
     */
    public function testSetMailTransferWillReturnFluentInterface(): void
    {
        $mailTransfer = new MailTransfer();
        $mailBuilder = $this->getMailBuilder();

        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder->setMailTransfer($mailTransfer));
    }

    /**
     * @return void
     */
    public function testGetMailTransferWillReturnMailTransfer(): void
    {
        $mailTransfer = new MailTransfer();
        $mailBuilder = $this->getMailBuilder();
        $mailBuilder->setMailTransfer($mailTransfer);

        $this->assertInstanceOf(MailTransfer::class, $mailBuilder->getMailTransfer());
    }

    /**
     * @return void
     */
    public function testGetMailTransferWillThrowExceptionWhenMailTransferNotSet(): void
    {
        $this->expectException(MissingMailTransferException::class);

        $mailBuilder = $this->getMailBuilderWithoutMailTransfer();
        $mailBuilder->getMailTransfer();
    }

    /**
     * @return void
     */
    public function testSetSubjectWillSetSubjectOnMailTransfer(): void
    {
        $mailBuilder = $this->getMailBuilder();
        $mailBuilder->setSubject(static::SUBJECT);

        $this->assertSame(static::SUBJECT, $mailBuilder->getMailTransfer()->getSubject());
    }

    /**
     * @return void
     */
    public function testSetSubjectWillReturnFluentInterface(): void
    {
        $mailBuilder = $this->getMailBuilder();
        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder->setSubject(static::SUBJECT));
    }

    /**
     * @return void
     */
    public function testSetHtmlTemplateWillReturnFluentInterface(): void
    {
        $mailBuilder = $this->getMailBuilder();
        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder->setHtmlTemplate(static::TEMPLATE_NAME_HTML));
    }

    /**
     * @return void
     */
    public function testSetHtmlTemplateWillAddMailTemplateTransferToMailTransfer(): void
    {
        $mailBuilder = $this->getMailBuilder();
        $mailBuilder->setHtmlTemplate(static::TEMPLATE_NAME_HTML);

        $this->assertContainsOnly(MailTemplateTransfer::class, $mailBuilder->getMailTransfer()->getTemplates());
    }

    /**
     * @return void
     */
    public function testSetTextTemplateWillReturnFluentInterface(): void
    {
        $mailBuilder = $this->getMailBuilder();
        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder->setTextTemplate(static::TEMPLATE_NAME_TEXT));
    }

    /**
     * @return void
     */
    public function testSetTextTemplateWillAddMailTemplateTransferToMailTransfer(): void
    {
        $mailBuilder = $this->getMailBuilder();
        $mailBuilder->setTextTemplate(static::TEMPLATE_NAME_TEXT);

        $this->assertContainsOnly(MailTemplateTransfer::class, $mailBuilder->getMailTransfer()->getTemplates());
    }

    /**
     * @return void
     */
    public function testSetSenderWillReturnFluentInterface(): void
    {
        $mailBuilder = $this->getMailBuilder();
        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder->setSender(static::EMAIL, static::NAME));
    }

    /**
     * @return void
     */
    public function testSetSenderWillSetSenderTransferToMailTransfer(): void
    {
        $mailBuilder = $this->getMailBuilder();
        $mailBuilder->setSender(static::EMAIL, static::NAME);

        $this->assertInstanceOf(MailSenderTransfer::class, $mailBuilder->getMailTransfer()->getSender());
    }

    /**
     * @return void
     */
    public function testAddRecipientWillReturnFluentInterface(): void
    {
        $mailBuilder = $this->getMailBuilder();
        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder->addRecipient(static::EMAIL, static::NAME));
    }

    /**
     * @return void
     */
    public function testAddRecipientWillAddMailRecipientTransferToMailTransfer(): void
    {
        $mailBuilder = $this->getMailBuilder();
        $mailBuilder->addRecipient(static::EMAIL, static::NAME);

        $this->assertContainsOnly(MailRecipientTransfer::class, $mailBuilder->getMailTransfer()->getRecipients());
    }

    /**
     * @return void
     */
    public function testAddRecipientBccAddsBccToMailTransfer(): void
    {
        // Assign
        $mailBuilder = $this->getMailBuilder();
        $expectedBccCount = 1;

        // Act
        $mailBuilder->addRecipientBcc(static::BCC_EMAIL, static::BCC_NAME);

        // Assert
        $actualBccCount = $mailBuilder->getMailTransfer()->getRecipientBccs()->count();
        $this->assertSame($expectedBccCount, $actualBccCount);
    }

    /**
     * @return void
     */
    public function testBuildWillReturnMailTransfer(): void
    {
        $mailBuilder = $this->getMailBuilder();

        $this->assertInstanceOf(MailTransfer::class, $mailBuilder->build());
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilder
     */
    protected function getMailBuilderWithoutMailTransfer(): MailBuilder
    {
        $glossaryFacadeMock = $this->getGlossaryFacadeMock();
        $mailBuilder = new MailBuilder(
            $glossaryFacadeMock,
            $this->getConfigMock()
        );

        return $mailBuilder;
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilder
     */
    protected function getMailBuilder(): MailBuilder
    {
        $glossaryFacadeMock = $this->getGlossaryFacadeMock();
        $mailBuilder = new MailBuilder(
            $glossaryFacadeMock,
            $this->getConfigMock()
        );

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName('en_US');

        $mailTransfer = new MailTransfer();
        $mailTransfer->setLocale($localeTransfer);

        $mailBuilder->setMailTransfer($mailTransfer);

        return $mailBuilder;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Dependency\Facade\MailToGlossaryInterface
     */
    protected function getGlossaryFacadeMock(): MailToGlossaryInterface
    {
        $glossaryFacadeMock = $this->getMockBuilder(MailToGlossaryInterface::class)->setMethods(['hasTranslation', 'translate'])->getMock();
        $glossaryFacadeMock->method('hasTranslation')->willReturn(true);
        $glossaryFacadeMock->method('translate')->willReturnArgument(0);

        return $glossaryFacadeMock;
    }

    /**
     * @return \Spryker\Zed\Mail\MailConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getConfigMock(): MailConfig
    {
        $configMock = $this->getMockBuilder(MailConfig::class)
            ->setMethods([
                'getSenderName',
                'getSenderEmail',
            ])
            ->getMock();

        $configMock->method('getSenderName')->willReturn(static::NAME);
        $configMock->method('getSenderEmail')->willReturn(static::EMAIL);

        return $configMock;
    }
}
