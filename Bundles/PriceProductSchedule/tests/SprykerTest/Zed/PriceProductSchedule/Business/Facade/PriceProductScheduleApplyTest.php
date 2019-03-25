<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use SprykerTest\Shared\PriceProductSchedule\Helper\PriceProductScheduleDataHelper;

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

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule($productConcreteTransfer);

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

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule($productConcreteTransfer, [],
            $otherStore);
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
            $productConcreteTransfer,
            [
                PriceProductScheduleTransfer::ACTIVE_TO => new DateTime(),
                PriceProductScheduleTransfer::IS_CURRENT => true,
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            $productConcreteTransfer,
            [
                PriceProductScheduleTransfer::ACTIVE_TO => new DateTime(),
            ]
        );

        $priceProductScheduleTransfer3 = $this->tester->havePriceProductSchedule($productConcreteTransfer);

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

        $priceProductScheduleTransfer1 = $this->tester->havePriceProductSchedule($productConcreteTransfer1);

        $productConcreteTransfer2 = $this->tester->haveProduct();

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule($productConcreteTransfer2);

        $productConcreteTransfer3 = $this->tester->haveProduct();

        $priceProductScheduleTransfer3 = $this->tester->havePriceProductSchedule($productConcreteTransfer3);

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

        $priceProductScheduleTransfer1 = $this->tester->havePriceProductSchedule($productConcreteTransfer, [], null,
            null, null);

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule($productConcreteTransfer, [], null,
            null, null, PriceProductScheduleDataHelper::CHF_ISO_CODE);

        $productConcreteTransfer3 = $this->tester->haveProduct();

        $priceProductScheduleTransfer3 = $this->tester->havePriceProductSchedule($productConcreteTransfer3, [], null,
            null, null, PriceProductScheduleDataHelper::CHF_ISO_CODE, PriceProductScheduleDataHelper::PRICE_TYPE_ID);

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
