<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundleDiscountConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundleDiscountConnector
 * @group Business
 * @group Facade
 * @group ProductBundleDiscountConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductBundleDiscountConnectorFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var string
     */
    protected const TEST_ATTRIBUTE_KEY = 'test_attribute_key';

    /**
     * @var string
     */
    protected const TEST_ATTRIBUTE_VALUE = 'test_attribute_value';

    /**
     * @var \SprykerTest\Zed\ProductBundleDiscountConnector\ProductBundleDiscountConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandProductAttributeDiscountableItemsCollectionWillAddBundledItemsToCollection(): void
    {
        // Arrange
        $productAttributes = [static::TEST_ATTRIBUTE_KEY => static::TEST_ATTRIBUTE_VALUE];
        $productConcreteTransfer = $this->tester->haveFullProduct([
            ProductConcreteTransfer::ATTRIBUTES => $productAttributes,
        ]);
        $productBundleTransfer = $this->tester->haveProductBundle($productConcreteTransfer);

        $quoteTransfer = $this->createQuoteWithProductBundleItems($productBundleTransfer);
        $clauseTransfer = $this->tester->createClauseTransfer(static::TEST_ATTRIBUTE_KEY, static::TEST_ATTRIBUTE_VALUE);

        // Act
        $discountableItems = $this->tester->getFacade()->expandProductAttributeDiscountableItemCollectionWithBundledProducts(
            [],
            $quoteTransfer,
            $clauseTransfer,
        );

        // Assert
        $this->assertCount(3, $discountableItems);
    }

    /**
     * @return void
     */
    public function testExpandProductAttributeDiscountableItemsCollectionWillNotAddBundledItemsToCollection(): void
    {
        // Arrange
        $productAttributes = [static::TEST_ATTRIBUTE_KEY => static::TEST_ATTRIBUTE_VALUE];
        $productConcreteTransfer = $this->tester->haveFullProduct([
            ProductConcreteTransfer::ATTRIBUTES => $productAttributes,
        ]);
        $productBundleTransfer = $this->tester->haveProductBundle($productConcreteTransfer);

        $quoteTransfer = $this->createQuoteWithProductBundleItems($productBundleTransfer);
        $clauseTransfer = $this->tester->createClauseTransfer('random_attribute', 'random_value');

        // Act
        $discountableItems = $this->tester->getFacade()->expandProductAttributeDiscountableItemCollectionWithBundledProducts(
            [],
            $quoteTransfer,
            $clauseTransfer,
        );

        // Assert
        $this->assertCount(0, $discountableItems);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productBundleTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteWithProductBundleItems(ProductConcreteTransfer $productBundleTransfer): QuoteTransfer
    {
        $quoteTransfer = (new QuoteTransfer())->setPriceMode(static::PRICE_MODE_GROSS);
        foreach ($productBundleTransfer->getProductBundleOrFail()->getBundledProducts() as $bundledProduct) {
            $itemTransfer = (new ItemTransfer())
                ->setId($bundledProduct->getIdProductConcreteOrFail())
                ->setUnitGrossPrice(100)
                ->setUnitNetPrice(90);
            $quoteTransfer->addItem($itemTransfer);
        }

        $bundleItemTransfer = (new ItemTransfer())->setId($productBundleTransfer->getIdProductConcreteOrFail());

        return $quoteTransfer->addBundleItem($bundleItemTransfer);
    }
}
