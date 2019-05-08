<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PersistentCartShare;

use Codeception\Test\Unit;
use Spryker\Client\PersistentCartShare\PersistentCartShareClientInterface;
use Spryker\Client\PersistentCartShare\PersistentCartShareDependencyProvider;
use SprykerTest\Client\PersistentCartShare\Fixtures\Plugin\TestShareOptionPlugin;

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
    /**
     * @see \SprykerTest\Client\PersistentCartShare\Fixtures\Plugin\TestShareOptionPlugin::GROUP_TEST
     */
    protected const TEST_GROUP = 'group-test';

    /**
     * @see \SprykerTest\Client\PersistentCartShare\Fixtures\Plugin\TestShareOptionPlugin::KEY_TEST
     */
    protected const KEY_TEST = 'key-test';

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
        $this->tester->setDependency(
            PersistentCartShareDependencyProvider::PLUGINS_CART_SHARE_OPTION,
            function () {
                return [new TestShareOptionPlugin()];
            }
        );

        // Act
        $cartShareOptions = $this->getPersistentCartShareClient()->getCartShareOptions();

        // Assert
        $this->assertIsArray($cartShareOptions);
        $this->assertArrayHasKey(static::TEST_GROUP, $cartShareOptions);
        $this->assertContains(static::KEY_TEST, $cartShareOptions[static::TEST_GROUP]);
    }

    /**
     * @return \Spryker\Client\PersistentCartShare\PersistentCartShareClientInterface
     */
    protected function getPersistentCartShareClient(): PersistentCartShareClientInterface
    {
        return $this->tester->getLocator()->persistentCartShare()->client();
    }
}
