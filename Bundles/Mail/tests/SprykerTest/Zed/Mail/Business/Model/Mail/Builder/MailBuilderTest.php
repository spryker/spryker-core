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

/**
 * Auto-generated group annotations
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
    public const SUBJECT = 'subject';
    public const TEMPLATE_NAME_HTML = 'html.template.name';
    public const TEMPLATE_NAME_TEXT = 'text.template.name';
    public const EMAIL = 'email';
    public const NAME = 'name';

    /**
     * @return void
     */
    public function testInstantiation()
    {
        $mailBuilder = $this->getMailBuilder();

        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder);
    }

    /**
     * @return void
     */
    public function testSetMailTransferWillReturnFluentInterface()
    {
        $mailTransfer = new MailTransfer();
        $mailBuilder = $this->getMailBuilder();

        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder->setMailTransfer($mailTransfer));
    }

    /**
     * @return void
     */
    public function testGetMailTransferWillReturnMailTransfer()
    {
        $mailTransfer = new MailTransfer();
        $mailBuilder = $this->getMailBuilder();
        $mailBuilder->setMailTransfer($mailTransfer);

        $this->assertInstanceOf(MailTransfer::class, $mailBuilder->getMailTransfer());
    }

    /**
     * @return void
     */
    public function testGetMailTransferWillThrowExceptionWhenMailTransferNotSet()
    {
        $this->expectException(MissingMailTransferException::class);

        $mailBuilder = $this->getMailBuilderWithoutMailTransfer();
        $mailBuilder->getMailTransfer();
    }

    /**
     * @return void
     */
    public function testSetSubjectWillSetSubjectOnMailTransfer()
    {
        $mailBuilder = $this->getMailBuilder();
        $mailBuilder->setSubject(static::SUBJECT);

        $this->assertSame(static::SUBJECT, $mailBuilder->getMailTransfer()->getSubject());
    }

    /**
     * @return void
     */
    public function testSetSubjectWillReturnFluentInterface()
    {
        $mailBuilder = $this->getMailBuilder();
        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder->setSubject(static::SUBJECT));
    }

    /**
     * @return void
     */
    public function testSetHtmlTemplateWillReturnFluentInterface()
    {
        $mailBuilder = $this->getMailBuilder();
        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder->setHtmlTemplate(static::TEMPLATE_NAME_HTML));
    }

    /**
     * @return void
     */
    public function testSetHtmlTemplateWillAddMailTemplateTransferToMailTransfer()
    {
        $mailBuilder = $this->getMailBuilder();
        $mailBuilder->setHtmlTemplate(static::TEMPLATE_NAME_HTML);

        $this->assertContainsOnly(MailTemplateTransfer::class, $mailBuilder->getMailTransfer()->getTemplates());
    }

    /**
     * @return void
     */
    public function testSetTextTemplateWillReturnFluentInterface()
    {
        $mailBuilder = $this->getMailBuilder();
        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder->setTextTemplate(static::TEMPLATE_NAME_TEXT));
    }

    /**
     * @return void
     */
    public function testSetTextTemplateWillAddMailTemplateTransferToMailTransfer()
    {
        $mailBuilder = $this->getMailBuilder();
        $mailBuilder->setTextTemplate(static::TEMPLATE_NAME_TEXT);

        $this->assertContainsOnly(MailTemplateTransfer::class, $mailBuilder->getMailTransfer()->getTemplates());
    }

    /**
     * @return void
     */
    public function testSetSenderWillReturnFluentInterface()
    {
        $mailBuilder = $this->getMailBuilder();
        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder->setSender(static::EMAIL, static::NAME));
    }

    /**
     * @return void
     */
    public function testSetSenderWillSetSenderTransferToMailTransfer()
    {
        $mailBuilder = $this->getMailBuilder();
        $mailBuilder->setSender(static::EMAIL, static::NAME);

        $this->assertInstanceOf(MailSenderTransfer::class, $mailBuilder->getMailTransfer()->getSender());
    }

    /**
     * @return void
     */
    public function testAddRecipientWillReturnFluentInterface()
    {
        $mailBuilder = $this->getMailBuilder();
        $this->assertInstanceOf(MailBuilderInterface::class, $mailBuilder->addRecipient(static::EMAIL, static::NAME));
    }

    /**
     * @return void
     */
    public function testAddRecipientWillAddMailRecipientTransferToMailTransfer()
    {
        $mailBuilder = $this->getMailBuilder();
        $mailBuilder->addRecipient(static::EMAIL, static::NAME);

        $this->assertContainsOnly(MailRecipientTransfer::class, $mailBuilder->getMailTransfer()->getRecipients());
    }

    /**
     * @return void
     */
    public function testBuildWillReturnMailTransfer()
    {
        $mailBuilder = $this->getMailBuilder();

        $this->assertInstanceOf(MailTransfer::class, $mailBuilder->build());
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilder
     */
    protected function getMailBuilderWithoutMailTransfer()
    {
        $glossaryFacadeMock = $this->getGlossaryFacadeMock();
        $mailBuilder = new MailBuilder($glossaryFacadeMock);

        return $mailBuilder;
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilder
     */
    protected function getMailBuilder()
    {
        $glossaryFacadeMock = $this->getGlossaryFacadeMock();
        $mailBuilder = new MailBuilder($glossaryFacadeMock);

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
    protected function getGlossaryFacadeMock()
    {
        $glossaryFacadeMock = $this->getMockBuilder(MailToGlossaryInterface::class)->setMethods(['hasTranslation', 'translate'])->getMock();
        $glossaryFacadeMock->method('hasTranslation')->willReturn(true);
        $glossaryFacadeMock->method('translate')->willReturnArgument(0);

        return $glossaryFacadeMock;
    }
}
