<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PriceProductSalesOrderAmendment\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductResolveConditionsTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use SprykerTest\Client\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group PriceProductSalesOrderAmendment
 * @group Client
 * @group ResolveOrderAmendmentPriceTest
 * Add your own group annotations below this line
 */
class ResolveOrderAmendmentPriceTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_PRODUCT_SKU = 'sku-1';

    /**
     * @var \SprykerTest\Client\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentClientTester
     */
    protected PriceProductSalesOrderAmendmentClientTester $tester;

    /**
     * @return void
     */
    public function testShouldResolveOrderAmendmentPriceWhenQuoteIsInOrderAmendmentProcess(): void
    {
        // Arrange
        $defaultPrice = 300;
        $originalSalesOrderItemUnitPrice = 100;

        $priceProductTransfer = (new PriceProductTransfer())
            ->setMoneyValue((new MoneyValueTransfer())->setGrossAmount($defaultPrice)->setNetAmount($defaultPrice));
        $quoteTransfer = $this->tester->prepareQuoteWithOriginalSalesOrderItemPrices([static::FAKE_PRODUCT_SKU => $originalSalesOrderItemUnitPrice]);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuote($quoteTransfer)
            ->setPriceProductResolveConditions((new PriceProductResolveConditionsTransfer())->setSku(static::FAKE_PRODUCT_SKU));

        // Act
        $priceProductTransfer = $this->tester->getClient()
            ->resolveOrderAmendmentPrice($priceProductTransfer, $priceProductFilterTransfer);

        // Assert
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
        $this->assertSame($originalSalesOrderItemUnitPrice, $moneyValueTransfer->getGrossAmount());
        $this->assertSame($originalSalesOrderItemUnitPrice, $moneyValueTransfer->getNetAmount());
    }

    /**
     * @return void
     */
    public function testShouldNotResolveOrderAmendmentPriceWithoutQuote(): void
    {
        // Arrange
        $defaultPrice = 300;

        $priceProductTransfer = (new PriceProductTransfer())
            ->setMoneyValue((new MoneyValueTransfer())->setGrossAmount($defaultPrice)->setNetAmount($defaultPrice));

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuote(null)
            ->setPriceProductResolveConditions((new PriceProductResolveConditionsTransfer())->setSku(static::FAKE_PRODUCT_SKU));

        // Act
        $priceProductTransfer = $this->tester->getClient()
            ->resolveOrderAmendmentPrice($priceProductTransfer, $priceProductFilterTransfer);

        // Assert
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
        $this->assertSame($defaultPrice, $moneyValueTransfer->getGrossAmount());
        $this->assertSame($defaultPrice, $moneyValueTransfer->getNetAmount());
    }

    /**
     * @return void
     */
    public function testShouldNotResolveOrderAmendmentPriceWithoutOrderAmendmentReference(): void
    {
        // Arrange
        $defaultPrice = 300;
        $originalSalesOrderItemUnitPrice = 100;

        $priceProductTransfer = (new PriceProductTransfer())
            ->setMoneyValue((new MoneyValueTransfer())->setGrossAmount($defaultPrice)->setNetAmount($defaultPrice));

        $quoteTransfer = $this->tester->prepareQuoteWithOriginalSalesOrderItemPrices([static::FAKE_PRODUCT_SKU => $originalSalesOrderItemUnitPrice]);
        $quoteTransfer->setAmendmentOrderReference(null);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuote($quoteTransfer)
            ->setPriceProductResolveConditions((new PriceProductResolveConditionsTransfer())->setSku(static::FAKE_PRODUCT_SKU));

        // Act
        $priceProductTransfer = $this->tester->getClient()
            ->resolveOrderAmendmentPrice($priceProductTransfer, $priceProductFilterTransfer);

        // Assert
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
        $this->assertSame($defaultPrice, $moneyValueTransfer->getGrossAmount());
        $this->assertSame($defaultPrice, $moneyValueTransfer->getNetAmount());
    }

    /**
     * @return void
     */
    public function testShouldNotResolveOrderAmendmentPriceWithoutPriceProductResolveConditions(): void
    {
        // Arrange
        $defaultPrice = 300;
        $originalSalesOrderItemUnitPrice = 100;

        $priceProductTransfer = (new PriceProductTransfer())
            ->setMoneyValue((new MoneyValueTransfer())->setGrossAmount($defaultPrice)->setNetAmount($defaultPrice));

        $quoteTransfer = $this->tester->prepareQuoteWithOriginalSalesOrderItemPrices([static::FAKE_PRODUCT_SKU => $originalSalesOrderItemUnitPrice]);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuote($quoteTransfer)
            ->setPriceProductResolveConditions(null);

        // Act
        $priceProductTransfer = $this->tester->getClient()
            ->resolveOrderAmendmentPrice($priceProductTransfer, $priceProductFilterTransfer);

        // Assert
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
        $this->assertSame($defaultPrice, $moneyValueTransfer->getGrossAmount());
        $this->assertSame($defaultPrice, $moneyValueTransfer->getNetAmount());
    }

    /**
     * @return void
     */
    public function testShouldNotResolveOrderAmendmentPriceWhenOrderPriceNotFound(): void
    {
        // Arrange
        $defaultPrice = 300;
        $originalSalesOrderItemUnitPrice = 100;

        $priceProductTransfer = (new PriceProductTransfer())
            ->setMoneyValue((new MoneyValueTransfer())->setGrossAmount($defaultPrice)->setNetAmount($defaultPrice));
        $quoteTransfer = $this->tester->prepareQuoteWithOriginalSalesOrderItemPrices(['sku-2' => $originalSalesOrderItemUnitPrice]);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuote($quoteTransfer)
            ->setPriceProductResolveConditions((new PriceProductResolveConditionsTransfer())->setSku(static::FAKE_PRODUCT_SKU));

        // Act
        $priceProductTransfer = $this->tester->getClient()
            ->resolveOrderAmendmentPrice($priceProductTransfer, $priceProductFilterTransfer);

        // Assert
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
        $this->assertSame($defaultPrice, $moneyValueTransfer->getGrossAmount());
        $this->assertSame($defaultPrice, $moneyValueTransfer->getNetAmount());
    }
}
