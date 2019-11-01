<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductNew\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductOfferBuilder;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductNew
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
    public function setUp(): void
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
        $productOfferTransfer = $this->tester->haveProductOffer();
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
        $productOfferTransfer = (new ProductOfferBuilder())->build();
        $productOfferTransfer->setIdProductOffer(null);

        // Act
        $this->tester->getFacade()->create($productOfferTransfer);

        // Assert
        $this->assertNotEmpty($productOfferTransfer->getIdProductOffer());
    }

    /**
     * @return void
     */
    protected function cleanProductOffer(): void
    {
        SpyProductOfferQuery::create()->deleteAll();
    }
}
