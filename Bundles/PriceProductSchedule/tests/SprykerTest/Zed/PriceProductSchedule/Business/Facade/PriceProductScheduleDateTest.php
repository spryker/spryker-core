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
     * @dataProvider priceProductScheduleShouldApplyForActiveDateRangesDataProvider
     *
     * @param \DateTime $activeFrom
     * @param \DateTime $activeTo
     *
     * @return void
     */
    public function testPriceProductScheduleShouldApplyForActiveDateRanges(
        DateTime $activeFrom,
        DateTime $activeTo
    ): void {
        // Assign
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT => $this->getPriceProductData(),
            PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
            PriceProductScheduleTransfer::ACTIVE_TO => $activeTo,
        ]);

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue(
            $priceProductScheduleEntity->isCurrent(),
            'Scheduled price with active date range should have been set as current.'
        );
    }

    /**
     * @dataProvider priceProductScheduleShouldNotApplyForNotActiveDateRangesDataProvider
     *
     * @param \DateTime $activeFrom
     * @param \DateTime $activeTo
     *
     * @return void
     */
    public function testPriceProductScheduleShouldNotApplyForNotActiveDateRanges(
        DateTime $activeFrom,
        DateTime $activeTo
    ): void {
        // Assign
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::ACTIVE_TO => $activeTo,
                PriceProductScheduleTransfer::PRICE_PRODUCT => $this->getPriceProductData(),
            ]
        );

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse(
            $priceProductScheduleEntity->isCurrent(),
            'Scheduled price with not active date range should not have been set as current.'
        );
    }

    /**
     * @return void
     */
    public function testActivePriceProductScheduleShouldStayActive(): void
    {
        // Assign
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => $this->getPriceProductData(),
            ]
        );

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue(
            $priceProductScheduleEntity->isCurrent(),
            'Current scheduled price with active date range should have been stay as current.'
        );
    }

    /**
     * @dataProvider priceProductScheduleShouldStayActiveForLessDurationDataProvider
     *
     * @param \DateTime $lowestActiveFrom
     * @param \DateTime $higherActiveFrom
     *
     * @return void
     */
    public function testPriceProductScheduleShouldStayActiveForLessDuration(
        DateTime $lowestActiveFrom,
        DateTime $higherActiveFrom
    ): void {
        // Assign
        $priceProductData = $this->getPriceProductData();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $lowestActiveFrom,
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+7 days')),
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductData,
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $higherActiveFrom,
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 year')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductData,
            ]
        );

        //Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        //Assert
        $priceProductScheduleEntity = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue(
            $priceProductScheduleEntity->isCurrent(),
            'Scheduled price with less duration should have been stay as current.'
        );

        $priceProductScheduleEntity2 = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse(
            $priceProductScheduleEntity2->isCurrent(),
            'Scheduled price with longest duration should not have been set as current.'
        );
    }

    /**
     * @dataProvider priceProductScheduleShouldToggleForLowestDurationDataProvider
     *
     * @param \DateTime $oldActiveFrom
     * @param \DateTime $activeFromForApply
     *
     * @return void
     */
    public function testPriceProductScheduleShouldToggleForLowestDuration(
        DateTime $oldActiveFrom,
        DateTime $activeFromForApply
    ): void {
        // Assign
        $priceProductData = $this->getPriceProductData();

        $activePriceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $oldActiveFrom,
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 year')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductData,
            ]
        );

        $priceProductScheduleTransferForApply = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFromForApply,
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+4 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductData,
            ]
        );

        //Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        //Assert
        $priceProductScheduleEntityForApply = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransferForApply->getIdPriceProductSchedule());
        $this->assertTrue(
            $priceProductScheduleEntityForApply->isCurrent(),
            'Scheduled price with less duration should have been set as current.'
        );

        $priceProductScheduleEntity2 = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($activePriceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse(
            $priceProductScheduleEntity2->isCurrent(),
            'Scheduled price with longest duration should not have been set as current.'
        );
    }

    /**
     * @dataProvider priceProductScheduleShouldApplyForLowestDurationDataProvider
     *
     * @param \DateTime $lowestActiveFrom
     * @param \DateTime $higherActiveFrom
     *
     * @return void
     */
    public function testPriceProductScheduleShouldApplyForLowestDuration(
        DateTime $lowestActiveFrom,
        DateTime $higherActiveFrom
    ): void {
        // Assign
        $priceProductData = $this->getAbstractPriceProductData();

        $priceProductScheduleTransferWithLowestDuration = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $lowestActiveFrom,
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductData,
            ]
        );

        $priceProductScheduleTransferWithHigherDuration = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $higherActiveFrom,
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+13 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductData,
            ]
        );

        //Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntityWithLowestDuration = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransferWithLowestDuration->getIdPriceProductSchedule());
        $this->assertTrue(
            $priceProductScheduleEntityWithLowestDuration->isCurrent(),
            'Scheduled price with less duration should have been set as current.'
        );

        $priceProductScheduleEntityWithHigherDuration = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransferWithHigherDuration->getIdPriceProductSchedule());
        $this->assertFalse(
            $priceProductScheduleEntityWithHigherDuration->isCurrent(),
            'Scheduled price with less duration should have been set as current.'
        );
    }

    /**
     * @return void
     */
    public function testEndedPriceProductScheduleShouldBeDisabledAndTheNewOneShouldApply(): void
    {
        // Assign
        $priceProductData = $this->getPriceProductData();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => new DateTime('-1 minute'),
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductData,
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => new DateTime('-1 minute'),
                PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductData,
            ]
        );

        $priceProductScheduleTransfer3 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-1 minute')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductData,
            ]
        );

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse(
            $priceProductScheduleEntity->isCurrent(),
            'Finished active scheduled price should have been set as not current.'
        );

        $priceProductScheduleEntity2 = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse(
            $priceProductScheduleEntity2->isCurrent(),
            'Finished not active scheduled price have been stay not current.'
        );

        $priceProductScheduleEntity3 = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer3->getIdPriceProductSchedule());
        $this->assertTrue(
            $priceProductScheduleEntity3->isCurrent(),
            'Scheduled price with active date range should have been set as current.'
        );
    }

    /**
     * @return array
     */
    protected function getPriceProductData(): array
    {
        $productConcreteTransfer = $this->tester->haveProduct();
        $currencyId = $this->tester->haveCurrency();
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $priceType = $this->tester->havePriceType();

        return [
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::PRICE_TYPE => [
                PriceTypeTransfer::NAME => $priceType->getName(),
                PriceTypeTransfer::ID_PRICE_TYPE => $priceType->getIdPriceType(),
            ],
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                MoneyValueTransfer::FK_CURRENCY => $currencyId,
                MoneyValueTransfer::GROSS_AMOUNT => 120,
                MoneyValueTransfer::NET_AMOUNT => 100,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getAbstractPriceProductData(): array
    {
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $currencyId = $this->tester->haveCurrency();
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $priceType = $this->tester->havePriceType();

        return [
            PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            PriceProductTransfer::PRICE_TYPE => [
                PriceTypeTransfer::NAME => $priceType->getName(),
                PriceTypeTransfer::ID_PRICE_TYPE => $priceType->getIdPriceType(),
            ],
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                MoneyValueTransfer::FK_CURRENCY => $currencyId,
                MoneyValueTransfer::GROSS_AMOUNT => 120,
                MoneyValueTransfer::NET_AMOUNT => 100,
            ],
        ];
    }

    /**
     * @return array
     */
    public function priceProductScheduleShouldApplyForActiveDateRangesDataProvider(): array
    {
        return [
            'active scheduled date range' => [
                (new DateTime('-5 days')),
                (new DateTime('+5 days')),
            ],
            'active scheduled current dates' => [
                (new DateTime('-1 minute')),
                (new DateTime('+1 minute')),
            ],
        ];
    }

    /**
     * @return array
     */
    public function priceProductScheduleShouldNotApplyForNotActiveDateRangesDataProvider(): array
    {
        return [
            'scheduled date range in future' => [
                (new DateTime('+5 days')),
                (new DateTime('+10 days')),
            ],
            'scheduled date range in past' => [
                (new DateTime('-10 days')),
                (new DateTime('-5 days')),
            ],
        ];
    }

    /**
     * @return array
     */
    public function priceProductScheduleShouldStayActiveForLessDurationDataProvider(): array
    {
        return [
            'with one hour range' => [
                (new DateTime('-1 hour')),
                (new DateTime('-2 hour')),
            ],
            'with 10 days range' => [
                (new DateTime('-5 days')),
                (new DateTime('-10 days')),
            ],
            'with 1 month range' => [
                (new DateTime('-5 days')),
                (new DateTime('-1 month')),
            ],
            'with 1 year range' => [
                (new DateTime('-1 month')),
                (new DateTime('-1 year')),
            ],
        ];
    }

    /**
     * @return array
     */
    public function priceProductScheduleShouldToggleForLowestDurationDataProvider(): array
    {
        return [
            'with one hour range' => [
                (new DateTime('-2 hour')),
                (new DateTime('-1 hour')),
            ],
            'with 10 days range' => [
                (new DateTime('-10 days')),
                (new DateTime('-5 days')),
            ],
            'with 1 month range' => [
                (new DateTime('-1 month')),
                (new DateTime('-5 days')),
            ],
            'with 1 year range' => [
                (new DateTime('-1 year')),
                (new DateTime('-1 month')),
            ],
        ];
    }

    /**
     * @return array
     */
    public function priceProductScheduleShouldApplyForLowestDurationDataProvider(): array
    {
        return [
            'with one hour range' => [
                (new DateTime('-1 hour')),
                (new DateTime('-2 hour')),
            ],
            'with 10 days range' => [
                (new DateTime('-5 days')),
                (new DateTime('-10 days')),
            ],
            'with 1 month range' => [
                (new DateTime('-5 days')),
                (new DateTime('-1 month')),
            ],
            'with 1 year range' => [
                (new DateTime('-1 month')),
                (new DateTime('-1 year')),
            ],
        ];
    }
}
