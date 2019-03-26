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
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;

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
     * @return void
     */
    public function testActiveScheduledDateRangesWithEqualDurationAndDifferentGrossPrices()
    {
        $activeFrom = new DateTime();
        $activeFrom->modify('-1 hour');

        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::MONEY_VALUE => [
                        MoneyValueTransfer::GROSS_AMOUNT => 100,
                    ],
                ],
            ]
        );
        $activeFrom2 = new DateTime();
        $activeFrom2->modify('-1 hour');

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom2,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::MONEY_VALUE => [
                        MoneyValueTransfer::GROSS_AMOUNT => 200,
                    ],
                ],
            ]
        );

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->applyScheduledPrices();

        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent());

        $priceProductScheduleEntity2 = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity2->isCurrent());
    }

    /**
     * @return void
     */
    public function testTwoActiveScheduledDateRangesWithEqualDurationAndDifferentNetPrices()
    {
        $activeFrom = new DateTime();
        $activeFrom->modify('-1 hour');

        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::MONEY_VALUE => [
                        MoneyValueTransfer::GROSS_AMOUNT => 100,
                        MoneyValueTransfer::NET_AMOUNT => 100,
                    ],
                ],
            ]
        );

        $activeFrom2 = new DateTime();
        $activeFrom2->modify('-1 hour');

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom2,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::MONEY_VALUE => [
                        MoneyValueTransfer::GROSS_AMOUNT => 100,
                        MoneyValueTransfer::NET_AMOUNT => 200,
                    ],
                ],
            ]
        );

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->applyScheduledPrices();

        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent());

        $priceProductScheduleEntity2 = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity2->isCurrent());
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function getPriceProductScheduleQuery(): SpyPriceProductScheduleQuery
    {
        return new SpyPriceProductScheduleQuery();
    }
}
