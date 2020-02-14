<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferSearch\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantProductAbstractTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

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
    protected const KEY_MERCHANT_NAMES = 'names';
    protected const KEY_MERCHANT_REFERENCES = 'references';

    /**
     * @var \SprykerTest\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetMerchantProductAbstractsByProductAbstractIds(): void
    {
        // Arrange
        $productConcrete1 = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $productConcrete2 = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);

        $merchant = $this->tester->haveMerchant();
        $this->tester->haveMerchantProfile($merchant, [
            MerchantProfileTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchant->getIdMerchant(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcrete1->getSku(),
            ProductOfferTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchant->getIdMerchant(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcrete2->getSku(),
            ProductOfferTransfer::IS_ACTIVE => true,
        ]);

        $merchantProductAbstractTransfer1 = (new MerchantProductAbstractTransfer())
            ->setIdProductAbstract($productConcrete1->getFkProductAbstract())
            ->setMerchantNames([$merchant->getName()])
            ->setMerchantReferences([$merchant->getMerchantReference()]);

        $merchantProductAbstractTransfer2 = (new MerchantProductAbstractTransfer())
            ->setIdProductAbstract($productConcrete2->getFkProductAbstract())
            ->setMerchantNames([$merchant->getName()])
            ->setMerchantReferences([$merchant->getMerchantReference()]);

        $expectedResult = [
            $merchantProductAbstractTransfer2,
            $merchantProductAbstractTransfer1,
        ];

        // Act
        $merchantProductAbstractTransfers = $this->tester
            ->getFacade()
            ->getMerchantProductAbstractsByProductAbstractIds([
                $productConcrete1->getFkProductAbstract(),
                $productConcrete2->getFkProductAbstract(),
            ]);

        // Assert
        $this->assertIsArray($merchantProductAbstractTransfers);
        $this->assertEquals($merchantProductAbstractTransfers, $expectedResult);
    }
}
