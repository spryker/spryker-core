<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Mail\Business\Model\Mailer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilder;
use Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionGetInterface;
use Spryker\Zed\Mail\Business\Model\Mailer\MailHandler;
use Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionGetInterface;
use Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface;
use Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Mail
 * @group Business
 * @group Model
 * @group Mailer
 * @group MailerTest
 * Add your own group annotations below this line
 */
class MailerTest extends Unit
{
    public const MAIL_TYPE_A = 'mail type a';

    /**
     * @return void
     */
    public function testInstantiation()
    {
        $mailBuilderMock = $this->getMailBuilderMock();
        $mailTypeCollectionMock = $this->getMailTypeCollectionMock();
        $mailProviderCollectionMock = $this->getMailProviderCollectionMock();

        $instance = new MailHandler($mailBuilderMock, $mailTypeCollectionMock, $mailProviderCollectionMock);

        $this->assertInstanceOf(MailHandler::class, $instance);
    }

    /**
     * @return void
     */
    public function testHandleMailWillDoNothingWhenMailTypeNotInCollection()
    {
        $mailerMock = $this->getMailerWhichIsNotExecuted();
        $mailTransfer = $this->getMailTransfer();

        $mailerMock->handleMail($mailTransfer);
    }

    /**
     * @return void
     */
    public function testHandleMailWillCallBuildOnMailTypeWhenMailTypeInCollection()
    {
        $mailBuilderMock = $this->getMailBuilderMock();
        $mailer = new MailHandler($mailBuilderMock, $this->getMailTypeCollectionWithMailMock(), $this->getMailProviderCollectionWithProviderMock());
        $mailTransfer = $this->getMailTransfer();

        $mailer->handleMail($mailTransfer);
    }

    /**
     * @return void
     */
    public function testHandleMailWillCallSendOnProviderWhenMailTypeInCollection()
    {
        $mailBuilderMock = $this->getMailBuilderMock();
        $mailer = new MailHandler($mailBuilderMock, $this->getMailTypeCollectionWithMailMock(), $this->getMailProviderCollectionWithProviderMock());
        $mailTransfer = $this->getMailTransfer();

        $mailer->handleMail($mailTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Business\Model\Mailer\MailHandler
     */
    protected function getMailerWhichIsNotExecuted()
    {
        $mailTypeCollectionMock = $this->getMockBuilder(MailHandler::class)
            ->setMethods(['buildMail', 'sendMail'])
            ->setConstructorArgs([
                $this->getMailBuilderMock(),
                $this->getMailTypeCollectionWithoutMailMock(),
                $this->getMailProviderCollectionMock(),
            ])
            ->getMock();

        $mailTypeCollectionMock->expects($this->never())->method('buildMail');
        $mailTypeCollectionMock->expects($this->never())->method('sendMail');

        return $mailTypeCollectionMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionGetInterface
     */
    protected function getMailTypeCollectionMock()
    {
        $mailTypeCollectionMock = $this->getMockBuilder(MailTypeCollectionGetInterface::class)->setMethods(['has', 'get'])->getMock();

        return $mailTypeCollectionMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionGetInterface
     */
    protected function getMailTypeCollectionWithoutMailMock()
    {
        $mailTypeCollectionMock = $this->getMailTypeCollectionMock();
        $mailTypeCollectionMock->expects($this->once())->method('has')->willReturn(false);

        return $mailTypeCollectionMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionGetInterface
     */
    protected function getMailTypeCollectionWithMailMock()
    {
        $mailTypeCollectionMock = $this->getMailTypeCollectionMock();
        $mailTypeCollectionMock->expects($this->once())->method('has')->willReturn(true);
        $mailTypeCollectionMock->expects($this->once())->method('get')->willReturn($this->getMailTypeMock());

        return $mailTypeCollectionMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionGetInterface
     */
    protected function getMailProviderCollectionMock()
    {
        $mailProviderCollectionMock = $this->getMockBuilder(MailProviderCollectionGetInterface::class)->setMethods(['getProviderForMailType'])->getMock();

        return $mailProviderCollectionMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionGetInterface
     */
    protected function getMailProviderCollectionWithProviderMock()
    {
        $mailProviderCollectionMock = $this->getMailProviderCollectionMock();
        $mailProviderCollectionMock->expects($this->once())->method('getProviderForMailType')->willReturn(
            [$this->getProviderMock()]
        );

        return $mailProviderCollectionMock;
    }

    /**
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function getMailTransfer()
    {
        $mailTransfer = new MailTransfer();
        $mailTransfer->setType(self::MAIL_TYPE_A);

        return $mailTransfer;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface
     */
    protected function getProviderMock()
    {
        $providerMock = $this->getMockBuilder(MailProviderPluginInterface::class)->setMethods(['sendMail'])->getMock();

        return $providerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface
     */
    protected function getMailBuilderMock()
    {
        $mailBuilderMock = $this->getMockBuilder(MailBuilder::class)->setMethods(['build'])->disableOriginalConstructor()->getMock();
        $mailBuilderMock->method('build')->willReturn($this->getMailTransfer());

        return $mailBuilderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface
     */
    protected function getMailTypeMock()
    {
        $mailTypeMock = $this->getMockBuilder(MailTypePluginInterface::class)->setMethods(['build', 'getName'])->getMock();
        $mailTypeMock->expects($this->once())->method('build');

        return $mailTypeMock;
    }
}
