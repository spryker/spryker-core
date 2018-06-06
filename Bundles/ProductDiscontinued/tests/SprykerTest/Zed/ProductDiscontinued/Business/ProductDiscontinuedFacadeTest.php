<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinued\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManager;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductDiscontinued
 * @group Business
 * @group Facade
 * @group ProductDiscontinuedFacadeTest
 * Add your own group annotations below this line
 */
class ProductDiscontinuedFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductDiscontinued\ProductDiscontinuedBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcrete;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManager
     */
    protected $productDiscontinuedEntityManager;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->productConcrete = $this->tester->haveProduct();
        $this->productDiscontinuedEntityManager = new ProductDiscontinuedEntityManager();
    }

    /**
     * @return void
     */
    public function testProductCanBeDiscontinued()
    {
        // Arrange
        $productDiscontinuedRequestTransfer = (new ProductDiscontinuedRequestTransfer())
            ->setIdProduct($this->productConcrete->getIdProductConcrete());

        // Act
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinuedRequestTransfer);

        // Assert
        $this->assertTrue($productDiscontinuedResponseTransfer->getIsSuccessful());
        $this->assertInstanceOf(ProductDiscontinuedTransfer::class, $productDiscontinuedResponseTransfer->getProductDiscontinued());
    }

    /**
     * @return void
     */
    public function testProductCanBeUndiscontinued()
    {
        // Arrange
        $productDiscontinuedRequestTransfer = (new ProductDiscontinuedRequestTransfer())
            ->setIdProduct($this->productConcrete->getIdProductConcrete());
        $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinuedRequestTransfer);

        // Act
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()->unmarkProductAsDiscontinued($productDiscontinuedRequestTransfer);

        // Assert
        $this->assertTrue($productDiscontinuedResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testGetProductDiscontinuedByProductId()
    {
        // Arrange
        $productDiscontinuedRequestTransfer = (new ProductDiscontinuedRequestTransfer())
            ->setIdProduct($this->productConcrete->getIdProductConcrete());
        $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinuedRequestTransfer);

        // Act
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()->findProductDiscontinuedByProductId($productDiscontinuedRequestTransfer);

        // Assert
        $this->assertTrue($productDiscontinuedResponseTransfer->getIsSuccessful());
        $this->assertInstanceOf(ProductDiscontinuedTransfer::class, $productDiscontinuedResponseTransfer->getProductDiscontinued());
    }

    /**
     * @return void
     */
    public function testGetProductDiscontinuedCollectionFilteredById()
    {
        // Arrange
        $productDiscontinuedRequestTransfer = (new ProductDiscontinuedRequestTransfer())
            ->setIdProduct($this->productConcrete->getIdProductConcrete());
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinuedRequestTransfer);
        $productDiscontinuedCriteriaFilterTransfer = (new ProductDiscontinuedCriteriaFilterTransfer())
            ->setIds([$productDiscontinuedResponseTransfer->getProductDiscontinued()->getIdProductDiscontinued()]);

        // Act
        $productDiscontinuedCollectionTransfer = $this->tester->getFacade()->findProductDiscontinuedCollection($productDiscontinuedCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $productDiscontinuedCollectionTransfer->getProductDiscontinueds());
    }

    /**
     * @return void
     */
    public function testProductDeactivatedAfterActiveUntilDatePassed()
    {
        // Arrange
        $productDiscontinuedRequestTransfer = (new ProductDiscontinuedRequestTransfer())
            ->setIdProduct($this->productConcrete->getIdProductConcrete());
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinuedRequestTransfer);
        $productDiscontinuedTransfer = $productDiscontinuedResponseTransfer->getProductDiscontinued();
        $productDiscontinuedTransfer->setActiveUntil(date('Y-m-d', strtotime('-1 Day')));
        $this->productDiscontinuedEntityManager->saveProductDiscontinued($productDiscontinuedTransfer);

        // Act
        $this->tester->getFacade()->deactivateDiscontinuedProducts();
        $loadedProduct = $this->tester->getProductFacade()->getProductConcrete($this->productConcrete->getSku());

        // Assert
        $this->assertFalse($loadedProduct->getIsActive());
    }
}
