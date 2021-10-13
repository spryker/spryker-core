<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business\MerchantFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Business
 * @group MerchantFacade
 * @group FilterPriceProductMerchantRelationsTest
 * Add your own group annotations below this line
 */
class FilterPriceProductMerchantRelationsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Merchant\MerchantBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFilterPriceProductMerchantRelationsWithActivatedMerchantReturnsArrayWithMerchantPrices(): void
    {
        // Arrange
        $activatedMerchantTransfer1 = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true]);
        $activatedMerchantTransfer2 = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true]);
        $priceProductMerchantRelationshipStorageTransfers = [
            $this->tester->createPriceProductMerchantRelationshipStorageTransfer($activatedMerchantTransfer1->getIdMerchantOrFail(), 300),
            $this->tester->createPriceProductMerchantRelationshipStorageTransfer($activatedMerchantTransfer2->getIdMerchantOrFail(), 500),
        ];

        // Act
        $filteredPriceProductMerchantRelationshipStorageTransfers = $this->tester->getFacade()->filterPriceProductMerchantRelations($priceProductMerchantRelationshipStorageTransfers);

        // Assert
        $this->assertSame(2, count($filteredPriceProductMerchantRelationshipStorageTransfers));
    }

    /**
     * @return void
     */
    public function testFilterPriceProductMerchantRelationsWithDeactivatedMerchantReturnsArrayWithoutNonActiveMerchantPrices(): void
    {
        // Arrange
        $activatedMerchantTransfer = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true]);
        $deactivatedMerchantTransfer = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => false]);
        $activatedMerchantPrice = 300;
        $deactivatedMerchantPrice = 500;
        $priceProductMerchantRelationshipStorageTransfers = [
            $this->tester->createPriceProductMerchantRelationshipStorageTransfer($activatedMerchantTransfer->getIdMerchantOrFail(), $activatedMerchantPrice),
            $this->tester->createPriceProductMerchantRelationshipStorageTransfer($deactivatedMerchantTransfer->getIdMerchantOrFail(), $deactivatedMerchantPrice),
        ];

        // Act
        $filteredPriceProductMerchantRelationshipStorageTransfers = $this->tester->getFacade()->filterPriceProductMerchantRelations($priceProductMerchantRelationshipStorageTransfers);

        // Assert
        $this->assertSame(1, count($filteredPriceProductMerchantRelationshipStorageTransfers));
        $priceProductMerchantRelationshipStorageTransfer = array_pop($filteredPriceProductMerchantRelationshipStorageTransfers);
        /** @var \Generated\Shared\Transfer\PriceProductMerchantRelationshipValueTransfer $priceProductMerchantRelationshipValueTransfer */
        $priceProductMerchantRelationshipValueTransfer = $priceProductMerchantRelationshipStorageTransfer->getUngroupedPrices()->offsetGet(0);
        $this->assertSame($activatedMerchantTransfer->getIdMerchant(), $priceProductMerchantRelationshipValueTransfer->getFkMerchant());
        $this->assertSame($activatedMerchantPrice, $priceProductMerchantRelationshipValueTransfer->getGrossPrice());
    }
}
