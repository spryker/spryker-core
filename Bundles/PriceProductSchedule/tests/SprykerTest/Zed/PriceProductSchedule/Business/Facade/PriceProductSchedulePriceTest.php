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
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected $spyPriceProductScheduleQuery;

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

        $this->priceProductScheduleFacade = $this->tester->getFacade();
        $this->spyPriceProductScheduleQuery = $this->tester->getPriceProductScheduleQuery();
        $this->priceProductFacade = $this->tester->getLocator()->priceProduct()->facade();
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleWithLowestGrossPriceShouldApply(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-1 hour')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                    PriceProductTransfer::PRICE_TYPE => [
                        PriceTypeTransfer::NAME => $this->priceProductFacade->getDefaultPriceTypeName(),
                        PriceTypeTransfer::ID_PRICE_TYPE => $this->tester->getPriceTypeId($this->priceProductFacade->getDefaultPriceTypeName()),
                    ],
                    PriceProductTransfer::MONEY_VALUE => [
                        MoneyValueTransfer::GROSS_AMOUNT => 100,
                    ],
                ],
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-1 hour')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                    PriceProductTransfer::PRICE_TYPE => [
                        PriceTypeTransfer::NAME => $this->priceProductFacade->getDefaultPriceTypeName(),
                        PriceTypeTransfer::ID_PRICE_TYPE => $this->tester->getPriceTypeId($this->priceProductFacade->getDefaultPriceTypeName()),
                    ],
                    PriceProductTransfer::MONEY_VALUE => [
                        MoneyValueTransfer::GROSS_AMOUNT => 200,
                    ],
                ],
            ]
        );

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent(), 'Scheduled price with lowest gross price should have been set as current.');

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity2->isCurrent(), 'Scheduled price with biggest gross price should not have been set as current.');
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleWithLowestNetPriceShouldApply(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-1 hour')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                    PriceProductTransfer::PRICE_TYPE => [
                        PriceTypeTransfer::NAME => $this->priceProductFacade->getDefaultPriceTypeName(),
                        PriceTypeTransfer::ID_PRICE_TYPE => $this->tester->getPriceTypeId($this->priceProductFacade->getDefaultPriceTypeName()),
                    ],
                    PriceProductTransfer::MONEY_VALUE => [
                        MoneyValueTransfer::GROSS_AMOUNT => 100,
                        MoneyValueTransfer::NET_AMOUNT => 100,
                    ],
                ],
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-1 hour')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                    PriceProductTransfer::PRICE_TYPE => [
                        PriceTypeTransfer::NAME => $this->priceProductFacade->getDefaultPriceTypeName(),
                        PriceTypeTransfer::ID_PRICE_TYPE => $this->tester->getPriceTypeId($this->priceProductFacade->getDefaultPriceTypeName()),
                    ],
                    PriceProductTransfer::MONEY_VALUE => [
                        MoneyValueTransfer::GROSS_AMOUNT => 100,
                        MoneyValueTransfer::NET_AMOUNT => 200,
                    ],
                ],
            ]
        );

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent(), 'Scheduled price with lowest net price should have been set as current.');

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity2->isCurrent(), 'Scheduled price with biggest net price should not have been set as current.');
    }
}
