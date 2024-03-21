<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\PriceProductAbstractMerchantRelationEventResourceBulkRepositoryPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductMerchantRelationshipStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group PriceProductAbstractMerchantRelationEventResourceBulkRepositoryPluginTest
 * Add your own group annotations below this line
 */
class PriceProductAbstractMerchantRelationEventResourceBulkRepositoryPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensurePriceProductMerchantRelationshipTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testGetDataReturnsTransferAccordingToOffsetAndLimit(): void
    {
        if ($this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('PriceProductMerchantRelationshipHelper::haveProductAbstractPriceProductMerchantRelationship is not compatible with Dynamic Store. Tech Debt ticket created.');
        }
        // Arrange
        $this->tester->haveProductAbstractPriceProductMerchantRelationship();
        $priceProductTransfer = $this->tester->haveProductAbstractPriceProductMerchantRelationship();
        $this->tester->haveProductAbstractPriceProductMerchantRelationship();

        // Act
        $priceProductMerchantRelationshipTransfers = (new PriceProductAbstractMerchantRelationEventResourceBulkRepositoryPlugin())
            ->getData(1, 1);

        // Assert
        $this->assertCount(1, $priceProductMerchantRelationshipTransfers);
        $this->assertArrayHasKey(0, $priceProductMerchantRelationshipTransfers);
        $this->assertSame(
            $priceProductTransfer->getMoneyValueOrFail()->getIdEntityOrFail(),
            $priceProductMerchantRelationshipTransfers[0]->getFkPriceProductStoreOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testGetDataDoesNotDuplicateDataWhenMerchantRelationshipHasTwoBusinessUnits(): void
    {
        if ($this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('PriceProductMerchantRelationshipHelper::haveProductAbstractPriceProductMerchantRelationship is not compatible with Dynamic Store. Tech Debt ticket created.');
        }
        // Arrange
        $priceProductTransfer = $this->tester->haveProductAbstractPriceProductMerchantRelationship();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $this->tester->haveMerchantRelationshipToCompanyBusinessUnit(
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
            $priceProductTransfer->getPriceDimensionOrFail()->getIdMerchantRelationshipOrFail(),
        );

        // Act
        $priceProductMerchantRelationshipTransfers = (new PriceProductAbstractMerchantRelationEventResourceBulkRepositoryPlugin())
            ->getData(0, 2);

        // Assert
        $this->assertCount(1, $priceProductMerchantRelationshipTransfers);
    }
}
