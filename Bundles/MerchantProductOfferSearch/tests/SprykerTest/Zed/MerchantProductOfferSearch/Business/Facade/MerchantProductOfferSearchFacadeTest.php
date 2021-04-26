<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferSearch\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;

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
     */
    protected const KEY_MERCHANT_NAMES = 'names';

    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\Mapper\ProductAbstractMerchantMapper::KEY_MERCHANT_REFERENCES
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
}
