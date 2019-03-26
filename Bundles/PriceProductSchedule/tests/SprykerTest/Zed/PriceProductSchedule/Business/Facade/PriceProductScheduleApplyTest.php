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
 * @group PriceProductScheduleApplyTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleApplyTest extends Unit
{
    public const CHF_ISO_CODE = 'CHF';

    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testActivePriceProductScheduleShouldApply()
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
    public function testDifferentStore()
    {
        $otherStore = $this->tester->haveStore();
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $otherStore->getIdStore(),
                ],
            ],
        ]);

        $priceProductScheduleFacade = $this->tester->getFacade();

        $priceProductScheduleFacade->applyScheduledPrices();
        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());

        $this->assertFalse($priceProductScheduleEntity->isCurrent());
    }

    /**
     * @return void
     */
    public function testScheduledDateRangesEndTheSameTimeWhenAnotherOneStarts()
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
     * @return void
     */
    public function testActiveScheduledDateRangesForDifferentProducts()
    {
        $productConcreteTransfer1 = $this->tester->haveProduct();

        $priceProductScheduleTransfer1 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer1->getFkProductAbstract(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer1->getFkProductAbstract(),
            ],
        ]);

        $productConcreteTransfer2 = $this->tester->haveProduct();

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer2->getFkProductAbstract(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer2->getFkProductAbstract(),
            ],
        ]);

        $productConcreteTransfer3 = $this->tester->haveProduct();

        $priceProductScheduleTransfer3 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer3->getFkProductAbstract(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer3->getFkProductAbstract(),
            ],
        ]);

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->applyScheduledPrices();

        $priceProductScheduleEntity1 = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer1->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity1->isCurrent());

        $priceProductScheduleEntity2 = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity2->isCurrent());

        $priceProductScheduleEntity3 = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer3->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity3->isCurrent());
    }

    /**
     * @return void
     */
    public function testScheduledDateRangesWithDifferentProductsCurrenciesAndPriceTypes()
    {
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer1 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            ],
        ]);

        $otherCurrency = $this->tester->getLocator()->currency()->facade()->fromIsoCode(static::CHF_ISO_CODE);

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_CURRENCY => $otherCurrency->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $otherCurrency,
                ],
            ],
        ]);

        $productConcreteTransfer3 = $this->tester->haveProduct();

        $priceProductScheduleTransfer3 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer3->getFkProductAbstract(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer3->getFkProductAbstract(),
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_CURRENCY => $otherCurrency->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $otherCurrency,
                ],
            ],
        ]);

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->applyScheduledPrices();

        $priceProductScheduleEntity1 = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer1->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity1->isCurrent());

        $priceProductScheduleEntity2 = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity2->isCurrent());

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
