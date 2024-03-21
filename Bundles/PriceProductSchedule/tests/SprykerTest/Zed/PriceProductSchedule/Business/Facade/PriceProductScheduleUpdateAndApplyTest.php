<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSchedule
 * @group Business
 * @group Facade
 * @group PriceProductScheduleUpdateAndApplyTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleUpdateAndApplyTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface
     */
    protected $priceProductScheduleFacade;

    /**
     * @var \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $storeTransfer;

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
        $this->tester->ensureDatabaseTableIsEmpty();
        $this->priceProductScheduleFacade = $this->tester->getFacade();
        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
        $this->storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->priceProductFacade = $this->tester->getLocator()->priceProduct()->facade();
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleAbstractUpdateAndApplyShouldSetDefaultPriceFromScheduledPrice(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();
        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'c1']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);
        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $defaultPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);
        $priceProductScheduleData = [
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $this->storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 300,
                    MoneyValueTransfer::GROSS_AMOUNT => 300,
                ],
            ],
        ];
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule($priceProductScheduleData);
        $priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->setNetAmount(500);

        // Act
        $this->priceProductScheduleFacade
            ->updateAndApplyPriceProductSchedule($priceProductScheduleTransfer);

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getAbstractSku())
            ->setPriceTypeName($defaultPriceTypeTransfer->getName())
            ->setCurrencyIsoCode($currencyTransfer->getCode())
            ->setStoreName($this->storeTransfer->getName());
        $actualPriceProductTransfer = $this->priceProductFacade
            ->findPriceProductFor($priceProductFilterTransfer);
        $this->assertSame(
            500,
            $actualPriceProductTransfer->getMoneyValue()->getNetAmount(),
            'Default price does not match expected value.',
        );
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleConcreteUpdateAndApplyShouldSetDefaultPriceFromScheduledPrice(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();
        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'c2']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);
        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $defaultPriceTypeTransfer,
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);
        $priceProductScheduleData = [
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $this->storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 300,
                    MoneyValueTransfer::GROSS_AMOUNT => 300,
                ],
            ],
        ];
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule($priceProductScheduleData);
        $priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->setNetAmount(500);

        // Act
        $this->priceProductScheduleFacade
            ->updateAndApplyPriceProductSchedule($priceProductScheduleTransfer);

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceTypeName($defaultPriceTypeTransfer->getName())
            ->setCurrencyIsoCode($currencyTransfer->getCode())
            ->setStoreName($this->storeTransfer->getName());
        $actualPriceProductTransfer = $this->priceProductFacade
            ->findPriceProductFor($priceProductFilterTransfer);
        $this->assertSame(
            500,
            $actualPriceProductTransfer->getMoneyValue()->getNetAmount(),
            'Default price does not match expected value.',
        );
    }
}
