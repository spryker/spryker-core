<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductConcreteConditionsTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\Product\ProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Facade
 * @group GetProductConcreteCollectionFacadeTest
 * Add your own group annotations below this line
 */
class GetProductConcreteCollectionFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Product\ProductBusinessTester
     */
    protected ProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetProductConcreteCollectionFiltersProductConcretesBySkus(): void
    {
        // Arrange
        $productConcreteTransfers = $this->createTwoProductConcreteTransfers();
        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaTransfer())->setProductConcreteConditions(
            (new ProductConcreteConditionsTransfer())->addSku($productConcreteTransfers[0]->getSku()),
        );

        // Act
        $productConcreteCollectionTransfer = $this->tester
            ->getProductFacade()
            ->getProductConcreteCollection($productConcreteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productConcreteCollectionTransfer->getProducts());
        $this->assertSame($productConcreteTransfers[0]->getSku(), $productConcreteCollectionTransfer->getProducts()->getIterator()->current()->getSku());
    }

    /**
     * @return void
     */
    public function testGetProductConcreteCollectionFiltersProductConcretesByLocaleNames(): void
    {
        // Arrange
        $productConcreteTransfers = $this->createTwoProductConcreteTransfers();
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => 'zu_ZA']);

        $productConcreteConditionsTransfer = (new ProductConcreteConditionsTransfer())
            ->addSku($productConcreteTransfers[0]->getSku())
            ->addSku($productConcreteTransfers[1]->getSku())
            ->addLocaleName($localeTransfer->getLocaleName());

        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaTransfer())
            ->setProductConcreteConditions($productConcreteConditionsTransfer);

        // Act
        $productConcreteCollectionTransfer = $this->tester
            ->getProductFacade()
            ->getProductConcreteCollection($productConcreteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $productConcreteCollectionTransfer->getProducts());
    }

    /**
     * @return void
     */
    public function testGetProductConcreteCollectionFiltersProductConcretesByExistingLocaleName(): void
    {
        // Arrange
        $productConcreteTransfers = $this->createTwoProductConcreteTransfers();

        $productConcreteConditionsTransfer = (new ProductConcreteConditionsTransfer())
            ->addSku($productConcreteTransfers[0]->getSku())
            ->addSku($productConcreteTransfers[1]->getSku())
            ->addLocaleName('en_US');

        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaTransfer())
            ->setProductConcreteConditions($productConcreteConditionsTransfer);

        // Act
        $productConcreteCollectionTransfer = $this->tester
            ->getProductFacade()
            ->getProductConcreteCollection($productConcreteCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productConcreteCollectionTransfer->getProducts());
        $this->assertCount(1, $productConcreteCollectionTransfer->getProducts()->offsetGet(0)->getLocalizedAttributes());
        $this->assertCount(1, $productConcreteCollectionTransfer->getProducts()->offsetGet(1)->getLocalizedAttributes());
    }

    /**
     * @return void
     */
    public function testGetProductConcreteCollectionPaginatesProductConcretes(): void
    {
        // Arrange
        $productConcreteTransfers = $this->createTwoProductConcreteTransfers();

        $productConcreteConditionsTransfer = (new ProductConcreteConditionsTransfer())
            ->addSku($productConcreteTransfers[0]->getSku())
            ->addSku($productConcreteTransfers[1]->getSku());

        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaTransfer())
            ->setProductConcreteConditions($productConcreteConditionsTransfer)
            ->setPagination((new PaginationTransfer())->setLimit(1)->setOffset(0));

        // Act
        $productConcreteCollectionTransfer = $this->tester
            ->getProductFacade()
            ->getProductConcreteCollection($productConcreteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productConcreteCollectionTransfer->getProducts());
    }

    /**
     * @return void
     */
    public function testGetProductConcreteCollectionSortsProductConcretesBySku(): void
    {
        // Arrange
        $productConcreteTransfers = $this->createTwoProductConcreteTransfers('abcdefg', 'bacdefg');

        $productConcreteConditionsTransfer = (new ProductConcreteConditionsTransfer())
            ->addSku($productConcreteTransfers[0]->getSku())
            ->addSku($productConcreteTransfers[1]->getSku());

        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaTransfer())
            ->setProductConcreteConditions($productConcreteConditionsTransfer)
            ->addSort((new SortTransfer())->setField(ProductConcreteTransfer::SKU)->setIsAscending(false));

        // Act
        $productConcreteCollectionTransfer = $this->tester
            ->getProductFacade()
            ->getProductConcreteCollection($productConcreteCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productConcreteCollectionTransfer->getProducts());
        $this->assertSame($productConcreteCollectionTransfer->getProducts()->offsetGet(0)->getSku(), 'bacdefg');
        $this->assertSame($productConcreteCollectionTransfer->getProducts()->offsetGet(1)->getSku(), 'abcdefg');
    }

    /**
     * @return void
     */
    public function testGetProductConcreteCollectionReturnsProductConcretesWithLocalizedAttributes(): void
    {
        // Arrange
        $productConcreteTransfers = $this->createTwoProductConcreteTransfers();

        $productConcreteConditionsTransfer = (new ProductConcreteConditionsTransfer())
            ->addSku($productConcreteTransfers[0]->getSku())
            ->addSku($productConcreteTransfers[1]->getSku());

        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaTransfer())
            ->setProductConcreteConditions($productConcreteConditionsTransfer);

        // Act
        $productConcreteCollectionTransfer = $this->tester
            ->getProductFacade()
            ->getProductConcreteCollection($productConcreteCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productConcreteCollectionTransfer->getProducts()->offsetGet(0)->getLocalizedAttributes());
        $this->assertCount(2, $productConcreteCollectionTransfer->getProducts()->offsetGet(1)->getLocalizedAttributes());
    }

    /**
     * @param string|null $sku1
     * @param string|null $sku2
     *
     * @return list<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function createTwoProductConcreteTransfers(?string $sku1 = null, ?string $sku2 = null): array
    {
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productConcreteTransfers = $this->tester->createProductTransferCollection($productAbstractTransfer);

        $firstProductConcrete = $productConcreteTransfers[0];
        $firstProductConcrete->setSku($sku1 ?? $firstProductConcrete->getSku());
        $firstIdProductConcrete = $this->tester->getProductFacade()->createProductConcrete($firstProductConcrete);
        $firstProductConcrete->setIdProductConcrete($firstIdProductConcrete);

        $secondProductConcrete = $productConcreteTransfers[1];
        $secondProductConcrete->setSku($sku2 ?? $secondProductConcrete->getSku());
        $secondIdProductConcrete = $this->tester->getProductFacade()->createProductConcrete($secondProductConcrete);
        $secondProductConcrete->setIdProductConcrete($secondIdProductConcrete);

        return [$firstProductConcrete, $secondProductConcrete];
    }
}
