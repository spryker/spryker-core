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
    protected const DATE_FORMAT = 'Y-m-d H:m:i';

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
     * @param string $activeFrom
     * @param string $activeTo
     *
     * @return void
     */
    public function testPriceProductScheduleShouldApplyForActiveDateRanges(string $activeFrom, string $activeTo): void
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
        $this->assertTrue($priceProductScheduleEntity->isCurrent());
    }

    /**
     * @dataProvider priceProductScheduleShouldNotApplyForNotActiveDateRangesDataProvider
     *
     * @param string $activeFrom
     * @param string $activeTo
     *
     * @return void
     */
    public function testPriceProductScheduleShouldNotApplyForNotActiveDateRanges(string $activeFrom, string $activeTo): void
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
        $this->assertFalse($priceProductScheduleEntity->isCurrent());
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
        $this->assertTrue($priceProductScheduleEntity->isCurrent());
    }

    /**
     * @dataProvider priceProductScheduleShouldStayActiveForLessDurationDataProvider
     *
     * @param string $activeFrom
     * @param string $activeFrom2
     *
     * @return void
     */
    public function testPriceProductScheduleShouldStayActiveForLessDuration(string $activeFrom, string $activeFrom2): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom2,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        //Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        //Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent());

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity2->isCurrent());
    }

    /**
     * @dataProvider priceProductScheduleShouldApplyForLessDurationDataProvider
     *
     * @param string $activeFrom
     * @param string $activeFrom2
     *
     * @return void
     */
    public function testPriceProductScheduleShouldApplyForLessDuration(string $activeFrom, string $activeFrom2): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom2,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        //Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        //Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity->isCurrent());

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity2->isCurrent());
    }

    /**
     * @dataProvider priceProductScheduleShouldApplyForLowestDurationDataProvider
     *
     * @param string $activeFrom
     * @param string $activeFrom2
     *
     * @return void
     */
    public function testPriceProductScheduleShouldApplyForLowestDuration(string $activeFrom, string $activeFrom2): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => $activeFrom2,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        //Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent());

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity2->isCurrent());
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
                PriceProductScheduleTransfer::IS_CURRENT => true,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_TO => new DateTime(),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        $priceProductScheduleTransfer3 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity->isCurrent());

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity2->isCurrent());

        $priceProductScheduleEntity3 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer3->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity3->isCurrent());
    }

    /**
     * @return array
     */
    public function priceProductScheduleShouldApplyForActiveDateRangesDataProvider(): array
    {
        return [
            'active scheduled date range' => [
                (new DateTime())->modify('-5 days')->format(static::DATE_FORMAT),
                (new DateTime())->modify('+5 days')->format(static::DATE_FORMAT),
            ],
            'active scheduled current dates' => [
                (new DateTime())->format('Y-m-d H:m:i'),
                (new DateTime())->format('Y-m-d H:m:i'),
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
                (new DateTime())->modify('+5 days')->format(static::DATE_FORMAT),
                (new DateTime())->modify('+10 days')->format(static::DATE_FORMAT),
            ],
            'scheduled date range in past' => [
                (new DateTime())->modify('-10 days')->format(static::DATE_FORMAT),
                (new DateTime())->modify('-5 days')->format(static::DATE_FORMAT),
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
                (new DateTime())->modify('-1 hour')->format(static::DATE_FORMAT),
                (new DateTime())->modify('-2 hour')->format(static::DATE_FORMAT),
            ],
            'with 10 days range' => [
                (new DateTime())->modify('-5 days')->format(static::DATE_FORMAT),
                (new DateTime())->modify('-10 days')->format(static::DATE_FORMAT),
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
                (new DateTime())->modify('-2 hour')->format(static::DATE_FORMAT),
                (new DateTime())->modify('-1 hour')->format(static::DATE_FORMAT),
            ],
            'with 10 days range' => [
                (new DateTime())->modify('-10 days')->format(static::DATE_FORMAT),
                (new DateTime())->modify('-5 days')->format(static::DATE_FORMAT),
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
                (new DateTime())->modify('-1 hour')->format(static::DATE_FORMAT),
                (new DateTime())->modify('-2 hour')->format(static::DATE_FORMAT),
            ],
            'with 10 days range' => [
                (new DateTime())->modify('-5 days')->format(static::DATE_FORMAT),
                (new DateTime())->modify('-10 days')->format(static::DATE_FORMAT),
            ],
        ];
    }
}
