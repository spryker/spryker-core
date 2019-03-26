<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
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
 * @group PriceProductScheduleDateTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleDateTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testActiveScheduledDateRange()
    {
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            ],
        ]);

        $priceProductScheduleFacade = $this->tester->getFacade();

        $priceProductScheduleFacade->applyScheduledPrices();
        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());

        $this->assertTrue($priceProductScheduleEntity->isCurrent());
    }

    /**
     * @return void
     */
    public function testActiveScheduledDates()
    {
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => new DateTime(),
                PriceProductScheduleTransfer::ACTIVE_TO => new DateTime(),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                ],
            ]
        );

        $priceProductScheduleFacade = $this->tester->getFacade();

        $priceProductScheduleFacade->applyScheduledPrices();
        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());

        $this->assertTrue($priceProductScheduleEntity->isCurrent());
    }

    /**
     * @return void
     */
    public function testScheduledDateRangeInFuture()
    {
        $activeFrom = new DateTime();
        $activeFrom->modify('+5 days');

        $activeTo = new DateTime();
        $activeTo->modify('+10 days');

        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::ACTIVE_TO => $activeTo,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                ],
            ]
        );

        $priceProductScheduleFacade = $this->tester->getFacade();

        $priceProductScheduleFacade->applyScheduledPrices();
        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());

        $this->assertFalse($priceProductScheduleEntity->isCurrent());
    }

    /**
     * @return void
     */
    public function testScheduledDateRangeInPast()
    {
        $activeFrom = new DateTime();
        $activeFrom->modify('-15 days');

        $activeTo = new DateTime();
        $activeTo->modify('-5 days');

        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::ACTIVE_TO => $activeTo,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                ],
            ]
        );

        $priceProductScheduleFacade = $this->tester->getFacade();

        $priceProductScheduleFacade->applyScheduledPrices();
        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());

        $this->assertFalse($priceProductScheduleEntity->isCurrent());
    }

    /**
     * @return void
     */
    public function testCurrentSchedule()
    {
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                ],
            ]
        );

        $priceProductScheduleFacade = $this->tester->getFacade();

        $priceProductScheduleFacade->applyScheduledPrices();
        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());

        $this->assertTrue($priceProductScheduleEntity->isCurrent());
    }

    /**
     * @return void
     */
    public function testActiveScheduledDateRangesOneWithLessDuration()
    {
        $activeFrom = new DateTime();
        $activeFrom->modify('-1 hour');

        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                ],
            ]
        );

        $activeFrom2 = new DateTime();
        $activeFrom2->modify('-2 hour');

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom2,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
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
    public function testActiveScheduledDateRangesOneWithLowestDuration()
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
                ],
            ]
        );

        $activeFrom2 = new DateTime();
        $activeFrom2->modify('-2 hour');

        $productConcreteTransfer2 = $this->tester->haveProduct();

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom2,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer2->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer2->getFkProductAbstract(),
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
    public function testActiveScheduledDateRangeEndsRightNow()
    {
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_TO => new DateTime(),
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                ],
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_TO => new DateTime(),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                ],
            ]
        );

        $priceProductScheduleTransfer3 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                ],
            ]
        );

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->applyScheduledPrices();

        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity->isCurrent());

        $priceProductScheduleEntity2 = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity2->isCurrent());

        $priceProductScheduleEntity3 = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer3->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity3->isCurrent());
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function getPriceProductScheduleQuery(): SpyPriceProductScheduleQuery
    {
        return new SpyPriceProductScheduleQuery();
    }
}
