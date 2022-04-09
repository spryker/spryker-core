<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductSearch\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\PageMapBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductSearch
 * @group Business
 * @group Facade
 * @group Facade
 * @group MerchantProductSearchFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductSearchFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductSearch\MerchantProductSearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetMerchantDataByProductAbstractIdsReturnsProductAbstractMerchantTransfers(): void
    {
        // Arrange
        $productConcrete1 = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $productConcrete2 = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);

        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
        ])->build();

        $merchant = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true, MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray()]);

        $this->tester->addMerchantProductRelation($merchant->getIdMerchant(), $productConcrete1->getFkProductAbstract());
        $this->tester->addMerchantProductRelation($merchant->getIdMerchant(), $productConcrete2->getFkProductAbstract());

        $productAbstractMerchantTransfer1 = (new ProductAbstractMerchantTransfer())
            ->setIdProductAbstract($productConcrete1->getFkProductAbstract())
            ->setMerchantNames([$storeTransfer->getName() => [$merchant->getName()]]);

        $productAbstractMerchantTransfer2 = (new ProductAbstractMerchantTransfer())
            ->setIdProductAbstract($productConcrete2->getFkProductAbstract())
            ->setMerchantNames([$storeTransfer->getName() => [$merchant->getName()]]);

        $expectedResult = [
            $productAbstractMerchantTransfer1,
            $productAbstractMerchantTransfer2,
        ];

        // Act
        $productAbstractMerchantTransfers = $this->tester
            ->getFacade()
            ->getMerchantDataByProductAbstractIds([
                $productConcrete1->getFkProductAbstract(),
                $productConcrete2->getFkProductAbstract(),
            ]);

        // Assert
        $this->assertIsArray($productAbstractMerchantTransfers);
        $this->assertEquals($expectedResult, $productAbstractMerchantTransfers);
    }

    /**
     * @return void
     */
    public function testGetMerchantDataByProductAbstractIdsForNotExistingAbstractProductReturnsEmptyArray(): void
    {
        // Arrange
        $notExistingProductAbstractIds = [0];
        $expectedProductAbstractMerchantTransfers = [];

        // Act
        $productAbstractMerchantTransfers = $this->tester
            ->getFacade()
            ->getMerchantDataByProductAbstractIds($notExistingProductAbstractIds);

        // Assert
        $this->assertEquals($expectedProductAbstractMerchantTransfers, $productAbstractMerchantTransfers);
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageMapSuccess(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
        ]);
        $this->tester->haveMerchantProduct([
            MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
        ]);
        $productData = [
            ProductConcretePageSearchTransfer::FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ];

        // Act
        $pageMapTransfer = $this->tester->getFacade()->expandProductConcretePageMap(
            new PageMapTransfer(),
            new PageMapBuilder(),
            $productData,
            new LocaleTransfer(),
        );

        // Assert
        $this->assertContains($merchantTransfer->getMerchantReference(), $pageMapTransfer->getMerchantReferences());
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageMapFailed(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);

        $productData = [
            ProductConcretePageSearchTransfer::FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ];

        // Act
        $pageMapTransfer = $this->tester->getFacade()->expandProductConcretePageMap(
            new PageMapTransfer(),
            new PageMapBuilder(),
            $productData,
            new LocaleTransfer(),
        );

        // Assert
        $this->assertCount(0, $pageMapTransfer->getMerchantReferences());
        $this->assertCount(0, $pageMapTransfer->getFullTextBoosted());
    }
}
