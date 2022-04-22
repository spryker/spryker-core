<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferSearch\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\PageMapBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferSearch
 * @group Business
 * @group Facade
 * @group Facade
 * @group MerchantProductOfferSearchFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferSearchFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\Mapper\ProductAbstractMerchantMapper::KEY_MERCHANT_NAMES
     *
     * @var string
     */
    protected const KEY_MERCHANT_NAMES = 'names';

    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\Mapper\ProductAbstractMerchantMapper::KEY_MERCHANT_REFERENCES
     *
     * @var string
     */
    protected const KEY_MERCHANT_REFERENCES = 'references';

    /**
     * @var \SprykerTest\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductAbstractMerchantsByProductAbstractIds(): void
    {
        // Arrange
        $productConcrete1 = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $productConcrete2 = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
        ])->build();

        $merchant = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true, MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray()]);

        $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchant->getMerchantReference(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcrete1->getSku(),
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);

        $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchant->getMerchantReference(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcrete2->getSku(),
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);

        $productAbstractMerchantTransfer1 = (new ProductAbstractMerchantTransfer())
            ->setIdProductAbstract($productConcrete1->getFkProductAbstract())
            ->setMerchantNames([$storeTransfer->getName() => [$merchant->getName()]])
            ->setMerchantReferences([$storeTransfer->getName() => [$merchant->getMerchantReference()]]);

        $productAbstractMerchantTransfer2 = (new ProductAbstractMerchantTransfer())
            ->setIdProductAbstract($productConcrete2->getFkProductAbstract())
            ->setMerchantNames([$storeTransfer->getName() => [$merchant->getName()]])
            ->setMerchantReferences([$storeTransfer->getName() => [$merchant->getMerchantReference()]]);

        $expectedResult = [
            $productAbstractMerchantTransfer1,
            $productAbstractMerchantTransfer2,
        ];

        // Act
        $productAbstractMerchantTransfers = $this->tester
            ->getFacade()
            ->getProductAbstractMerchantDataByProductAbstractIds([
                $productConcrete1->getFkProductAbstract(),
                $productConcrete2->getFkProductAbstract(),
            ]);

        // Assert
        $this->assertIsArray($productAbstractMerchantTransfers);
        $this->assertEquals($productAbstractMerchantTransfers, $expectedResult);
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageMapSuccess(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfer->getIdProductConcrete(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);

        $productData = [
            ProductConcretePageSearchTransfer::SKU => $productConcreteTransfer->getSku(),
            ProductConcretePageSearchTransfer::STORE => $storeTransfer->getName(),
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
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
        ]);
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfer->getIdProductConcrete(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        $productOfferStoreTransfer = $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);

        $productData = [
            ProductConcretePageSearchTransfer::SKU => $productConcreteTransfer->getIdProductConcrete(),
            ProductConcretePageSearchTransfer::STORE => $storeTransfer->getName(),
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
