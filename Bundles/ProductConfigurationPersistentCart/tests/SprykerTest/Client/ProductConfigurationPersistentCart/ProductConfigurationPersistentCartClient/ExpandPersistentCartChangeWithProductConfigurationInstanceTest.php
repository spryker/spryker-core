<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationStorage
 * @group ProductConfigurationStorageClient
 * @group ExpandPersistentCartChangeWithProductConfigurationInstanceTest
 * Add your own group annotations below this line
 */
class ExpandPersistentCartChangeWithProductConfigurationInstanceTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationPersistentCart\ProductConfigurationPersistentCartClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandsItemWithProductConfigurationInstance(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder([
            ProductConfigurationInstanceTransfer::PRICES => new ArrayObject(),
        ]))->build();

        $this->tester->getProductConfigurationStorageClient()->storeProductConfigurationInstanceBySku($productConcreteTransfer->getSku(), $productConfigurationInstanceTransfer);

        $itemTransfer = (new ItemBuilder())->build()->setSku($productConcreteTransfer->getSku());
        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())->addItem($itemTransfer);

        // Act
        $persistentCartChangeTransfer = $this->tester
            ->getClient()
            ->expandPersistentCartChangeWithProductConfigurationInstance($persistentCartChangeTransfer, []);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $persistentCartChangeTransfer->getItems()->offsetGet(0);

        $this->assertNotNull(
            $itemTransfer->getProductConfigurationInstance(),
            'Expects that item will be expanded with product configuration instance.'
        );
        $this->assertEquals(
            $productConfigurationInstanceTransfer,
            $itemTransfer->getProductConfigurationInstance(),
            'Expects that item will be expanded with product configuration instance.'
        );
    }

    /**
     * @return void
     */
    public function testIgnoresExpandWithoutItems(): void
    {
        // Arrange
        $persistentCartChangeTransfer = new PersistentCartChangeTransfer();

        // Act
        $persistentCartChangeTransfer = $this->tester
            ->getClient()
            ->expandPersistentCartChangeWithProductConfigurationInstance($persistentCartChangeTransfer, []);

        // Assert
        $this->assertEmpty(
            $persistentCartChangeTransfer->getItems(),
            'Expects no items in cart change transfer when call expander with empty cart change transfer.'
        );
    }

    /**
     * @return void
     */
    public function testExpandsItemWithEmptyInstance(): void
    {
        // Arrange
        $this->tester->setupStorageRedisConfig();
        $productConcreteTransfer = $this->tester->haveProduct();

        $itemTransfer = (new ItemBuilder())->build()->setSku($productConcreteTransfer->getSku());
        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())->addItem($itemTransfer);

        // Act
        $persistentCartChangeTransfer = $this->tester
            ->getClient()
            ->expandPersistentCartChangeWithProductConfigurationInstance($persistentCartChangeTransfer, []);

        // Assert
        $this->assertNull(
            $persistentCartChangeTransfer->getItems()->offsetGet(0)->getProductConfigurationInstance(),
            'Expects item without product configuration when no product configuration.'
        );
    }
}
