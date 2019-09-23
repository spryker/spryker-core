<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinued\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManager;

/**
 * Auto-generated group annotations
 *
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
    protected const NOTE_TEST = 'NOTE_TEST';

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
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->productConcrete = $this->tester->haveProduct();
        $this->localeTransfer = $this->tester->haveLocale();
        $this->productDiscontinuedEntityManager = new ProductDiscontinuedEntityManager();
    }

    /**
     * @return void
     */
    public function testProductCanBeDiscontinued(): void
    {
        // Arrange
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($this->productConcrete->getIdProductConcrete());

        // Act
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinueRequestTransfer);

        // Assert
        $this->assertTrue($productDiscontinuedResponseTransfer->getIsSuccessful());
        $this->assertInstanceOf(ProductDiscontinuedTransfer::class, $productDiscontinuedResponseTransfer->getProductDiscontinued());
    }

    /**
     * @return void
     */
    public function testProductCanBeUndiscontinued(): void
    {
        // Arrange
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($this->productConcrete->getIdProductConcrete());
        $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinueRequestTransfer);

        // Act
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()->unmarkProductAsDiscontinued($productDiscontinueRequestTransfer);

        // Assert
        $this->assertTrue($productDiscontinuedResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testGetProductDiscontinuedByProductId(): void
    {
        // Arrange
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($this->productConcrete->getIdProductConcrete());
        $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinueRequestTransfer);

        // Act
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()->findProductDiscontinuedByProductId($this->productConcrete->getIdProductConcrete());

        // Assert
        $this->assertTrue($productDiscontinuedResponseTransfer->getIsSuccessful());
        $this->assertInstanceOf(ProductDiscontinuedTransfer::class, $productDiscontinuedResponseTransfer->getProductDiscontinued());
    }

    /**
     * @return void
     */
    public function testGetProductDiscontinuedCollectionFilteredById(): void
    {
        // Arrange
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($this->productConcrete->getIdProductConcrete());
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinueRequestTransfer);
        $productDiscontinuedCriteriaFilterTransfer = (new ProductDiscontinuedCriteriaFilterTransfer())
            ->setIds([$productDiscontinuedResponseTransfer->getProductDiscontinued()->getIdProductDiscontinued()]);

        // Act
        $productDiscontinuedCollectionTransfer = $this->tester->getFacade()->findProductDiscontinuedCollection($productDiscontinuedCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $productDiscontinuedCollectionTransfer->getDiscontinuedProducts());
    }

    /**
     * @return void
     */
    public function testProductDeactivatedAfterActiveUntilDatePassed(): void
    {
        // Arrange
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($this->productConcrete->getIdProductConcrete());
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinueRequestTransfer);
        $productDiscontinuedTransfer = $productDiscontinuedResponseTransfer->getProductDiscontinued();
        $productDiscontinuedTransfer->setActiveUntil(date('Y-m-d', strtotime('-1 Day')));
        $this->productDiscontinuedEntityManager->saveProductDiscontinued($productDiscontinuedTransfer);

        // Act
        $this->tester->getFacade()->deactivateDiscontinuedProducts();
        $loadedProduct = $this->tester->getProductFacade()->getProductConcrete($this->productConcrete->getSku());

        // Assert
        $this->assertFalse($loadedProduct->getIsActive());
    }

    /**
     * @return void
     */
    public function testLocalizedNotesCanBeAddedToProductDiscontinued(): void
    {
        // Arrange
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($this->productConcrete->getIdProductConcrete());
        $productDiscontinuedTransfer = $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinueRequestTransfer)->getProductDiscontinued();
        $productDiscontinuedNoteTransfer = (new ProductDiscontinuedNoteTransfer())
            ->setFkProductDiscontinued($productDiscontinuedTransfer->getIdProductDiscontinued())
            ->setNote(static::NOTE_TEST)
            ->setFkLocale($this->localeTransfer->getIdLocale());

        // Act
        $this->tester->getFacade()->saveDiscontinuedNote($productDiscontinuedNoteTransfer);
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()->findProductDiscontinuedByProductId($this->productConcrete->getIdProductConcrete());
        $productDiscontinuedNoteTransfer = $productDiscontinuedResponseTransfer->getProductDiscontinued()->getProductDiscontinuedNotes()->offsetGet(0);

        // Assert
        $this->assertCount(1, $productDiscontinuedResponseTransfer->getProductDiscontinued()->getProductDiscontinuedNotes());
        $this->assertInstanceOf(ProductDiscontinuedNoteTransfer::class, $productDiscontinuedNoteTransfer);
    }
}
