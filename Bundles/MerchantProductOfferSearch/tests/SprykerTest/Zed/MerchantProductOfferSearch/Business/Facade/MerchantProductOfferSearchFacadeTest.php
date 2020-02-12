<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferSearch\Business\Facade;

use Codeception\Test\Unit;
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
    /**
     * @var \SprykerTest\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetMerchantNamesByProductAbstractIds(): void
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

        $expectedResult = [
            $productConcrete1->getFkProductAbstract() => [$merchant->getName()],
            $productConcrete2->getFkProductAbstract() => [$merchant->getName()],
        ];

        // Act
        $merchantNames = $this->tester
            ->getFacade()
            ->getMerchantNamesByProductAbstractIds(
                array_keys($expectedResult)
            );

        // Assert
        $this->assertIsArray($merchantNames);
        $this->assertEquals($merchantNames, $expectedResult);
    }

    /**
     * @return void
     */
    public function testGetMerchantReferencesByProductAbstractIds(): void
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

        $expectedResult = [
            $productConcrete1->getFkProductAbstract() => [$merchant->getMerchantReference()],
            $productConcrete2->getFkProductAbstract() => [$merchant->getMerchantReference()],
        ];

        // Act
        $merchantReferences = $this->tester
            ->getFacade()
            ->getMerchantReferencesByProductAbstractIds(
                array_keys($expectedResult)
            );

        // Assert
        $this->assertIsArray($merchantReferences);
        $this->assertEquals($merchantReferences, $expectedResult);
    }
}
