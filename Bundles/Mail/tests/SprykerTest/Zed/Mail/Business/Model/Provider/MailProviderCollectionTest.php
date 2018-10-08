<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Mail\Business\Model\Provider;

use Codeception\Test\Unit;
use Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollection;
use Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Mail
 * @group Business
 * @group Model
 * @group Provider
 * @group MailProviderCollectionTest
 * Add your own group annotations below this line
 */
class MailProviderCollectionTest extends Unit
{
    public const TYPE_A = 'type a';
    public const TYPE_B = 'type b';
    public const MAIL_TYPE_ALL = '*';

    /**
     * @return void
     */
    public function testAddProviderWithOneAcceptedTypeWillReturnFluentInterface()
    {
        $mailProviderCollection = $this->getMailProviderCollection();
        $mailProviderMock = $this->getMailProviderMock();

        $this->assertInstanceOf(MailProviderCollection::class, $mailProviderCollection->addProvider($mailProviderMock, $this->getAcceptedType()));
    }

    /**
     * @return void
     */
    public function testAddProviderWithMultipleAcceptedTypesWillReturnFluentInterface()
    {
        $mailProviderCollection = $this->getMailProviderCollection();
        $mailProviderMock = $this->getMailProviderMock();

        $this->assertInstanceOf(MailProviderCollection::class, $mailProviderCollection->addProvider($mailProviderMock, $this->getAcceptedTypes()));
    }

    /**
     * @return void
     */
    public function testGetProviderWillReturnProviderIfOneByTypeNameInCollection()
    {
        $mailProviderCollection = $this->getMailProviderCollectionWithTypeAProvider();
        $mailProviderPlugins = $mailProviderCollection->getProviderForMailType(static::TYPE_A);

        $this->assertCount(1, $mailProviderPlugins);
    }

    /**
     * @return void
     */
    public function testGetProviderWillReturnProviderWhichIsRegisteredForAllMailTypes()
    {
        $mailProviderCollection = $this->getMailProviderCollectionWithTypeAProvider();
        $mailProviderPlugins = $mailProviderCollection->getProviderForMailType(static::TYPE_A);

        $this->assertCount(1, $mailProviderPlugins);
    }

    /**
     * @return void
     */
    public function testGetProviderWillReturnEmptyArrayWhenNoProviderForGivenMailTypeInCollection()
    {
        $mailProviderCollection = $this->getMailProviderCollectionWithTypeAProvider();
        $mailProviderPlugins = $mailProviderCollection->getProviderForMailType(static::TYPE_B);

        $this->assertCount(0, $mailProviderPlugins);
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollection
     */
    protected function getMailProviderCollection()
    {
        $mailProviderCollection = new MailProviderCollection();

        return $mailProviderCollection;
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionGetInterface
     */
    protected function getMailProviderCollectionWithTypeAProvider()
    {
        $mailProviderMock = $this->getMailProviderMock();
        $mailProviderCollection = new MailProviderCollection();
        $mailProviderCollection->addProvider($mailProviderMock, static::TYPE_A);

        return $mailProviderCollection;
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionGetInterface
     */
    protected function getMailProviderCollectionWithProviderForAllMailTypes()
    {
        $mailProviderMock = $this->getMailProviderMock();
        $mailProviderCollection = new MailProviderCollection();
        $mailProviderCollection->addProvider($mailProviderMock, self::MAIL_TYPE_ALL);

        return $mailProviderCollection;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface
     */
    protected function getMailProviderMock()
    {
        $mailProviderMock = $this->getMockBuilder(MailProviderPluginInterface::class)->getMock();

        return $mailProviderMock;
    }

    /**
     * @return array
     */
    protected function getAcceptedTypes()
    {
        return [
            static::TYPE_A,
            static::TYPE_B,
        ];
    }

    /**
     * @return string
     */
    protected function getAcceptedType()
    {
        return static::TYPE_A;
    }
}
