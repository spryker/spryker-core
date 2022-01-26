<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductStorage\Communication\Plugin\Publisher\ProductStorageExtension;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\ProductStorage\MerchantProductConcreteStorageCollectionExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group ProductStorageExtension
 * @group MerchantProductConcreteStorageCollectionExpanderPluginTest
 * Add your own group annotations below this line
 */
class MerchantProductConcreteStorageCollectionExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductStorage\MerchantProductStorageTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandProductConcreteStorageCollectionWithMerchantReference(): void
    {
        // Arrange
        $merchantProductConcreteStorageCollectionExpanderPlugin = new MerchantProductConcreteStorageCollectionExpanderPlugin();
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantProductTransfer = $this->tester->haveMerchantProduct(
            [
                MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchantOrFail(),
                MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstractOrFail(),
            ],
        );

        $productConcreteStorageTransfer = new ProductConcreteStorageTransfer();
        $productConcreteStorageTransfer->setIdProductAbstract($merchantProductTransfer->getIdProductAbstract());

        // Act
        $productConcreteStorageTransfers = $merchantProductConcreteStorageCollectionExpanderPlugin->expand([$productConcreteStorageTransfer]);

        // Assert
        $this->assertSame($merchantTransfer->getMerchantReference(), $productConcreteStorageTransfers[0]->getMerchantReference());
    }
}
