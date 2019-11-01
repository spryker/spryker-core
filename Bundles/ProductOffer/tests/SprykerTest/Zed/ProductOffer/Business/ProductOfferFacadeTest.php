<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductNew\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductOfferBuilder;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\ProductNew\Business\ProductNewBusinessFactory;
use Spryker\Zed\ProductNew\ProductNewConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOffer
 * @group Business
 * @group Facade
 * @group ProductOfferFacadeTest
 * Add your own group annotations below this line
 */
class ProductOfferFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOffer\ProductOfferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanProductOffer();
    }

    /**
     * @return void
     */
    public function testFind(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveMerchantProductOffer(['fkMerchant' => $this->tester->haveMerchant()->getIdMerchant()]);
        $productOfferCriteriaFilterTransfer = new ProductOfferCriteriaFilterTransfer();
        $productOfferCriteriaFilterTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());

        // Act
        $productOfferCollectionTransfer = $this->tester->getFacade()->find($productOfferCriteriaFilterTransfer);
        // Assert
        $this->assertNotEmpty($productOfferCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testCreate(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $productOfferTransfer = (new ProductOfferBuilder(['fkMerchant' => $merchantTransfer->getIdMerchant()]))->build();
        $productOfferTransfer->setIdProductOffer(null);

        // Act
        $this->tester->getFacade()->create($productOfferTransfer);

        // Assert
        $this->assertNotEmpty($productOfferTransfer->getIdProductOffer());
    }

    /**
     * @return void
     */
    protected function cleanProductOffer()
    {
        SpyProductOfferQuery::create()->deleteAll();
    }
}
