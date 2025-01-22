<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractConditionsTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractRelationsTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SortTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Facade
 * @group GetProductAbstractCollectionFacadeTest
 * Add your own group annotations below this line
 */
class GetProductAbstractCollectionFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Product\ProductBusinessTester
     */
    protected $tester;

    /**
     * @var array<string, string>
     */
    protected const PRODUCT_NAME = [
        self::LOCALE_NAME_EN => 'Product name en_US',
        self::LOCALE_NAME_DE => 'Product name de_DE',
    ];

    /**
     * @var string
     */
    protected const LOCALE_NAME_DE = 'de_DE';

    /**
     * @var string
     */
    protected const LOCALE_NAME_EN = 'en_US';

    /**
     * @var string
     */
    protected const SKU_1 = 'test-sku1';

    /**
     * @var string
     */
    protected const SKU_2 = 'test-sku2';

    /**
     * @var string
     */
    protected const LOCALIZED_ATTRIBUTE_NAME = 'name';

    /**
     * @var string
     */
    protected const UNEXISTING_STORE_REFERENCE = 'store-doesnt-exists';

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerInterface
     */
    protected $messageBrokerFacade;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface
     */
    protected $eventFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setUpDatabase();
    }

    /**
     * @return void
     */
    public function testGetProductAbstractCollectionFiltersProductAbstractsBySkus(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $productAbstractCriteriaTransfer = (new ProductAbstractCriteriaTransfer())->setProductAbstractConditions(
            (new ProductAbstractConditionsTransfer())
                ->addSku($productAbstractTransfer->getSku()),
        );

        // Act
        $productAbstractCollectionTransfer = $this->tester->getProductFacade()
            ->getProductAbstractCollection($productAbstractCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productAbstractCollectionTransfer->getProductAbstracts());
        $this->assertSame($productAbstractCollectionTransfer->getProductAbstracts()->offsetGet(0)->getSku(), $productAbstractTransfer->getSku());
    }

    /**
     * @return void
     */
    public function testGetProductAbstractCollectionPaginatesProductAbstracts(): void
    {
        // Arrange
        $this->tester->haveProductAbstract();
        $this->tester->haveProductAbstract();
        $this->tester->haveProductAbstract();

        $productAbstractCriteriaTransfer = (new ProductAbstractCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())
                    ->setLimit(2)
                    ->setOffset(0),
            );

        // Act
        $productAbstractCollectionTransfer = $this->tester->getProductFacade()
            ->getProductAbstractCollection($productAbstractCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productAbstractCollectionTransfer->getProductAbstracts());
    }

    /**
     * @return void
     */
    public function testGetProductAbstractCollectionSortsProductAbstractsBySku(): void
    {
        // Arrange
        $this->tester->haveProductAbstract([ProductAbstractTransfer::SKU => 'abc']);
        $this->tester->haveProductAbstract([ProductAbstractTransfer::SKU => 'bac']);
        $this->tester->haveProductAbstract([ProductAbstractTransfer::SKU => 'cab']);

        $productAbstractCriteriaTransfer = (new ProductAbstractCriteriaTransfer())
            ->addSort(
                (new SortTransfer())->setField(ProductAbstractTransfer::SKU)
                    ->setIsAscending(false),
            )
            ->setProductAbstractConditions(
                (new ProductAbstractConditionsTransfer())
                    ->setSkus(['abc', 'bac', 'cab']),
            );

        // Act
        $productAbstractCollectionTransfer = $this->tester->getProductFacade()
            ->getProductAbstractCollection($productAbstractCriteriaTransfer);

        // Assert
        $this->assertCount(3, $productAbstractCollectionTransfer->getProductAbstracts());
        $this->assertSame($productAbstractCollectionTransfer->getProductAbstracts()->offsetGet(0)->getSku(), 'cab');
        $this->assertSame($productAbstractCollectionTransfer->getProductAbstracts()->offsetGet(1)->getSku(), 'bac');
        $this->assertSame($productAbstractCollectionTransfer->getProductAbstracts()->offsetGet(2)->getSku(), 'abc');
    }

    /**
     * @return void
     */
    public function testGetProductAbstractCollectionReturnsProductAbstractsWithProductConcretes(): void
    {
        // Arrange
        $expectedProductAbstractTransfer = $this->tester->haveProductAbstract([ProductAbstractTransfer::SKU => 'foo']);
        $expectedProductConcreteTransfer = $this->tester->haveProductConcrete([ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $expectedProductAbstractTransfer->getIdProductAbstract()]);

        $productAbstractCriteriaTransfer = (new ProductAbstractCriteriaTransfer())
            ->setProductAbstractConditions(
                (new ProductAbstractConditionsTransfer())
                    ->setSkus(['foo']),
            )
            ->setProductAbstractRelations(
                (new ProductAbstractRelationsTransfer())
                    ->setWithVariants(true),
            );

        // Act
        $productAbstractCollectionTransfer = $this->tester->getProductFacade()
            ->getProductAbstractCollection($productAbstractCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productAbstractCollectionTransfer->getProductAbstracts());
        $this->assertCount(1, $productAbstractCollectionTransfer->getProductConcretes());
        $this->assertSame('foo', $productAbstractCollectionTransfer->getProductAbstracts()->offsetGet(0)->getSku());
        $this->assertSame($expectedProductConcreteTransfer->getSku(), $productAbstractCollectionTransfer->getProductConcretes()->offsetGet(0)->getProductConcretes()->offsetGet(0)->getSku());
    }
}
