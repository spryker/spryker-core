<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSchedule
 * @group Business
 * @group Facade
 * @group PriceProductSchedulePriceTest
 * Add your own group annotations below this line
 */
class PriceProductSchedulePriceTest extends Unit
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
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty();

        $this->priceProductScheduleFacade = $this->tester->getFacade();
        $this->storeFacade = $this->tester->getLocator()->store()->facade();
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleWithLowestGrossPriceShouldApply(): void
    {
        // Assign
        $priceProductScheduleData = $this->getPriceProductScheduleData();

        $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::GROSS_AMOUNT] = 100;
        $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::NET_AMOUNT] = 200;

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule($priceProductScheduleData);

        $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::GROSS_AMOUNT] = 200;
        $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::NET_AMOUNT] = 200;

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule($priceProductScheduleData);

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue(
            $priceProductScheduleEntity->isCurrent(),
            'Scheduled price with lowest gross price should have been set as current.'
        );

        $priceProductScheduleEntity2 = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse(
            $priceProductScheduleEntity2->isCurrent(),
            'Scheduled price with biggest gross price should not have been set as current.'
        );
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleWithLowestNetPriceShouldApply(): void
    {
        // Assign
        $priceProductScheduleData = $this->getPriceProductScheduleData();

        $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::GROSS_AMOUNT] = 100;
        $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::NET_AMOUNT] = 100;

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule($priceProductScheduleData);

        $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::GROSS_AMOUNT] = 100;
        $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::NET_AMOUNT] = 200;

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule($priceProductScheduleData);

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue(
            $priceProductScheduleEntity->isCurrent(),
            'Scheduled price with lowest net price should have been set as current.'
        );

        $priceProductScheduleEntity2 = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse(
            $priceProductScheduleEntity2->isCurrent(),
            'Scheduled price with biggest net price should not have been set as current.'
        );
    }

    /**
     * @return array
     */
    protected function getPriceProductScheduleData(): array
    {
        $productConcreteTransfer = $this->tester->haveProduct();
        $currencyId = $this->tester->haveCurrency();
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $priceType = $this->tester->havePriceType();

        return [
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-1 hour')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $priceType->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $priceType->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                ],
            ],
        ];
    }
}
