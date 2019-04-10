<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
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
 * @group PriceProductScheduleListTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleListTest extends Unit
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
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->priceProductScheduleFacade = $this->tester->getFacade();
        $this->spyPriceProductScheduleQuery = $this->tester->getPriceProductScheduleQuery();
        $this->storeFacade = $this->tester->getLocator()->store()->facade();
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleFromInactivePriceProductScheduleListShouldNotApply(): void
    {
        // Assign
        $priceProductScheduleList = $this->tester->havePriceProductScheduleList([
            PriceProductScheduleListTransfer::IS_ACTIVE => false,
        ]);
        $productConcreteTransfer = $this->tester->haveProduct();
        $currencyId = $this->tester->haveCurrency();
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $priceType = $this->tester->havePriceType();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleList,
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
            ]
        );

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse(
            $priceProductScheduleEntity->isCurrent(),
            'Scheduled price with not active price product schedule list should not have been set as current.'
        );
    }
}
