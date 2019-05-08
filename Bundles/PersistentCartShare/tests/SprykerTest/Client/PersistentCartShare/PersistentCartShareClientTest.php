<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PersistentCartShare;

use Codeception\Test\Unit;
use Spryker\Client\PersistentCartShare\PersistentCartShareClientInterface;
use Spryker\Client\PersistentCartShare\PersistentCartShareDependencyProvider;
use Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group PersistentCartShare
 * @group PersistentCartShareClientTest
 * Add your own group annotations below this line
 */
class PersistentCartShareClientTest extends Unit
{
    protected const SHARE_OPTION_GROUP_VALUE = 'SHARE_OPTION_GROUP_VALUE';
    protected const KEY_VALUE = 'KEY_VALUE';

    /**
     * @var \SprykerTest\Client\PersistentCartShare\PersistentCartShareClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCartShareOptionsShouldReturnCorrectStructure(): void
    {
        // Arrange
        $this->registerCartShareOptionPlugin($this->createShareOptionPluginMock());

        // Act
        $cartShareOptions = $this->getPersistentCartShareClient()->getCartShareOptions();

        // Assert
        $this->assertArrayHasKey(static::SHARE_OPTION_GROUP_VALUE, $cartShareOptions);
        $this->assertContains(static::KEY_VALUE, $cartShareOptions[static::SHARE_OPTION_GROUP_VALUE]);
    }

    /**
     * @return \Spryker\Client\PersistentCartShare\PersistentCartShareClientInterface
     */
    protected function getPersistentCartShareClient(): PersistentCartShareClientInterface
    {
        return $this->tester->getLocator()->persistentCartShare()->client();
    }

    /**
     * @return \Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface
     */
    protected function createShareOptionPluginMock(): CartShareOptionPluginInterface
    {
        /** @var \Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface|\PHPUnit\Framework\MockObject\MockObject $cartShareOptionPluginMock */
        $cartShareOptionPluginMock = $this->getMockBuilder(CartShareOptionPluginInterface::class)
            ->setMethods(['getKey', 'getShareOptionGroup'])
            ->getMock();

        $cartShareOptionPluginMock
            ->method('getKey')
            ->willReturn(static::KEY_VALUE);

        $cartShareOptionPluginMock
            ->method('getShareOptionGroup')
            ->willReturn(static::SHARE_OPTION_GROUP_VALUE);

        return $cartShareOptionPluginMock;
    }

    /**
     * @param \Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface $shareOptionPluginMock
     *
     * @return void
     */
    protected function registerCartShareOptionPlugin(CartShareOptionPluginInterface $shareOptionPluginMock): void
    {
        $this->tester->setDependency(
            PersistentCartShareDependencyProvider::PLUGINS_CART_SHARE_OPTION,
            [
                $shareOptionPluginMock,
            ]
        );
    }
}
