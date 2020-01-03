<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\PriceProduct;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSchedule
 * @group Business
 * @group PriceProduct
 * @group ProductPriceUpdaterTest
 * Add your own group annotations below this line
 */
class ProductPriceUpdaterTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductUpdaterInterface
     */
    protected $productPriceUpdater;

    /**
     * @var \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productPriceUpdater = (new PriceProductScheduleBusinessFactory())->createProductPriceUpdater();
        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
        $this->priceProductFacade = $this->tester->getLocator()->priceProduct()->facade();
    }

    /**
     * @return void
     */
    public function testUpdateCurrentProductPriceShouldUpdateNetAndGrossPrices(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceTypeTransfer1 = $this->tester->havePriceType();
        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'AAA']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);

        $this->tester->havePriceProduct([
            PriceProductTransfer::ID_PRICE_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer1,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 125,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $priceTypeTransfer2 = $this->tester->havePriceType();

        $productPrice2 = $this->tester->havePriceProduct([
            PriceProductTransfer::ID_PRICE_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer2,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 200,
                MoneyValueTransfer::GROSS_AMOUNT => 250,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        // Act
        $this->productPriceUpdater->updateCurrentPriceProduct($productPrice2, $priceTypeTransfer1);

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceTypeName($priceTypeTransfer1->getName())
            ->setCurrencyIsoCode($currencyTransfer->getCode());

        $priceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        $this->assertEquals(
            $productPrice2->getMoneyValue()->getNetAmount(),
            $priceProductTransfer->getMoneyValue()->getNetAmount(),
            'The net price should be updated.'
        );
        $this->assertEquals(
            $productPrice2->getMoneyValue()->getGrossAmount(),
            $priceProductTransfer->getMoneyValue()->getGrossAmount(),
            'The gross price should be updated.'
        );
    }
}
