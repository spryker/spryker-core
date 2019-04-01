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
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected $spyPriceProductScheduleQuery;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->priceProductScheduleFacade = $this->tester->getFacade();
        $this->spyPriceProductScheduleQuery = $this->tester->getPriceProductScheduleQuery();
    }

    /**
     * @dataProvider priceProductScheduleShouldApplyForActiveDateRangesDataProvider
     *
     * @param \DateTime $activeFrom
     * @param \DateTime $activeTo
     *
     * @return void
     */
    public function testPriceProductScheduleShouldApplyForActiveDateRanges(DateTime $activeFrom, DateTime $activeTo): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ],
            PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
            PriceProductScheduleTransfer::ACTIVE_TO => $activeTo,
        ]);

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent(), 'Scheduled price with active date range should have been set as current.');
    }

    /**
     * @dataProvider priceProductScheduleShouldNotApplyForNotActiveDateRangesDataProvider
     *
     * @param \DateTime $activeFrom
     * @param \DateTime $activeTo
     *
     * @return void
     */
    public function testPriceProductScheduleShouldNotApplyForNotActiveDateRanges(DateTime $activeFrom, DateTime $activeTo): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::ACTIVE_TO => $activeTo,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity->isCurrent(), 'Scheduled price with not active date range should not have been set as current.');
    }

    /**
     * @return void
     */
    public function testActivePriceProductScheduleShouldStayActive(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent(), 'Current scheduled price with active date range should have been stay as current.');
    }

    /**
     * @dataProvider priceProductScheduleShouldStayActiveForLessDurationDataProvider
     *
     * @param \DateTime $activeFrom
     * @param \DateTime $activeFrom2
     *
     * @return void
     */
    public function testPriceProductScheduleShouldStayActiveForLessDuration(DateTime $activeFrom, DateTime $activeFrom2): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+7 days')),
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom2,
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+7 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        //Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        //Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent(), 'Scheduled price with less duration should have been stay as current.');

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity2->isCurrent(), 'Scheduled price with longest duration should not have been set as current.');
    }

    /**
     * @dataProvider priceProductScheduleShouldApplyForLessDurationDataProvider
     *
     * @param \DateTime $activeFrom
     * @param \DateTime $activeFrom2
     *
     * @return void
     */
    public function testPriceProductScheduleShouldApplyForLessDuration(DateTime $activeFrom, DateTime $activeFrom2): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+4 days')),
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom2,
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+4 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        //Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        //Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity->isCurrent(), 'Scheduled price with less duration should have been set as current.');

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity2->isCurrent(), 'Scheduled price with longest duration should not have been set as current.');
    }

    /**
     * @dataProvider priceProductScheduleShouldApplyForLowestDurationDataProvider
     *
     * @param \DateTime $activeFrom
     * @param \DateTime $activeFrom2
     *
     * @return void
     */
    public function testPriceProductScheduleShouldApplyForLowestDuration(DateTime $activeFrom, DateTime $activeFrom2): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom2,
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        //Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent(), 'Scheduled price with less duration should have been set as current.');

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity2->isCurrent(), 'Scheduled price with less duration should have been set as current.');
    }

    /**
     * @return void
     */
    public function testEndedPriceProductScheduleShouldBeEnabledAndTheNewOneShouldApply(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_TO => new DateTime(),
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_TO => new DateTime(),
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        $priceProductScheduleTransfer3 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity->isCurrent(), 'Finished active scheduled price should have been set as not current.');

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity2->isCurrent(), 'Finished not active scheduled price have been stay not current.');

        $priceProductScheduleEntity3 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer3->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity3->isCurrent(), 'Scheduled price with active date range should have been set as current.');
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
                (new DateTime()),
                (new DateTime()),
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
        ];
    }

    /**
     * @return array
     */
    public function priceProductScheduleShouldApplyForLessDurationDataProvider(): array
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
        ];
    }
}
