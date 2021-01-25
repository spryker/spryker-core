<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Mail\Business\Model\Mail;

use Codeception\Test\Unit;
use Spryker\Zed\Mail\Business\Exception\MailNotFoundException;
use Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollection;
use Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Mail
 * @group Business
 * @group Model
 * @group Mail
 * @group MailTypeCollectionTest
 * Add your own group annotations below this line
 */
class MailTypeCollectionTest extends Unit
{
    public const MAIL_TYPE_A = 'mail type a';

    /**
     * @return void
     */
    public function testAddMailTypeReturnFluentInterface(): void
    {
        $mailTypeCollection = $this->getMailCollection();
        $mailTypeMock = $this->getMailTypeMock();

        $this->assertInstanceOf(MailTypeCollection::class, $mailTypeCollection->add($mailTypeMock));
    }

    /**
     * @return void
     */
    public function testHasShouldReturnFalseWhenMailTypeNotInCollection(): void
    {
        $mailTypeCollection = $this->getMailCollection();

        $this->assertFalse($mailTypeCollection->has(static::MAIL_TYPE_A));
    }

    /**
     * @return void
     */
    public function testHasShouldReturnTrueWhenMailTypeInCollection(): void
    {
        $mailTypeMock = $this->getMailTypeMock();
        $mailTypeCollection = $this->getMailCollection();
        $mailTypeCollection->add($mailTypeMock);

        $this->assertTrue($mailTypeCollection->has(static::MAIL_TYPE_A));
    }

    /**
     * @return void
     */
    public function testGetByMailTypeShouldReturnMailTypeWhenMailTypeInCollection(): void
    {
        $mailTypeMock = $this->getMailTypeMock();
        $mailTypeCollection = $this->getMailCollection();
        $mailTypeCollection->add($mailTypeMock);

        $this->assertInstanceOf(MailTypePluginInterface::class, $mailTypeCollection->get(static::MAIL_TYPE_A));
    }

    /**
     * @return void
     */
    public function testGetByMailTypeShouldThrowExceptionWhenMailTypeNotInCollection(): void
    {
        $mailTypeCollection = $this->getMailCollection();

        $this->expectException(MailNotFoundException::class);
        $mailTypeCollection->get(static::MAIL_TYPE_A);
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollection
     */
    protected function getMailCollection(): MailTypeCollection
    {
        return new MailTypeCollection();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface
     */
    protected function getMailTypeMock(): MailTypePluginInterface
    {
        $mailTypeMock = $this->getMockBuilder(MailTypePluginInterface::class)->setMethods(['getName', 'build'])->getMock();
        $mailTypeMock->method('getName')->willReturn(static::MAIL_TYPE_A);

        return $mailTypeMock;
    }
}
