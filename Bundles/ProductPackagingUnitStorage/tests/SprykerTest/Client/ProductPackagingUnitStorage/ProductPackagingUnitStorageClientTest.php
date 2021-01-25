<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductPackagingUnitStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToStorageClientInterface;
use Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageClientInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductPackagingUnitStorage
 * @group ProductPackagingUnitStorageClientTest
 * Add your own group annotations below this line
 */
class ProductPackagingUnitStorageClientTest extends Unit
{
    protected const PACKAGING_TYPE = 'box';

    /**
     * @var \SprykerTest\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindProductPackagingUnitById(): void
    {
        // Arrange
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct();

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::FK_LEAD_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
        ]);

        $productPackagingUnitStorageToStorageClientBridge = $this->getMockBuilder(ProductPackagingUnitStorageToStorageClientInterface::class)->getMock();
        $this->tester->setStorageMock(
            $productPackagingUnitStorageToStorageClientBridge,
            (new ProductPackagingUnitStorageTransfer())->toArray()
        );

        // Act
        $productPackagingUnitStorageTransfer = $this->getProductPackagingUnitStorageClient()
            ->findProductPackagingUnitById(
                $boxProductConcreteTransfer->getIdProductConcrete()
            );

        // Assert
        $this->assertInstanceOf(ProductPackagingUnitStorageTransfer::class, $productPackagingUnitStorageTransfer);
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageClientInterface
     */
    protected function getProductPackagingUnitStorageClient(): ProductPackagingUnitStorageClientInterface
    {
        return $this->tester->getLocator()->productPackagingUnitStorage()->client();
    }
}
