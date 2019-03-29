<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\Model;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSchedule
 * @group Business
 * @group Model
 * @group PriceProductScheduleCleanerTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleCleanerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory
     */
    private $priceProductScheduleFactory;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Model\PriceProductScheduleCleanerInterface
     */
    private $priceProductScheduleCleaner;

    /**
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    private $priceProductScheduleQuery;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->priceProductScheduleFactory = new PriceProductScheduleBusinessFactory();
        $this->priceProductScheduleCleaner = $this->priceProductScheduleFactory->createPriceProductScheduleCleaner();
        $this->priceProductScheduleQuery = new SpyPriceProductScheduleQuery();
    }

    /**
     * @dataProvider priceProductScheduleCleanerShouldRemoveAllEntitiesBeforeDaysRetainedDataProvider
     *
     * @param array $data
     * @param int $daysRetained
     * @param int $expectedCount
     *
     * @return void
     */
    public function testPriceProductScheduleCleanerShouldRemoveAllEntitiesBeforeDaysRetained(array $data, int $daysRetained, int $expectedCount): void
    {
        // Assign
        foreach ($data as $priceProductScheduleData) {
            $productConcreteTransfer = $this->tester->haveProduct();

            $this->tester->havePriceProductSchedule(
                array_merge(
                    $priceProductScheduleData,
                    [
                        PriceProductScheduleTransfer::PRICE_PRODUCT => [
                            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                        ],
                    ]
                )
            );
        }

        // Act
        $this->priceProductScheduleCleaner->cleanAppliedScheduledPrices($daysRetained);

        // Assert
        $this->assertEquals($expectedCount, $this->priceProductScheduleQuery->find()->count());
    }

    /**
     * @return array
     */
    public function priceProductScheduleCleanerShouldRemoveAllEntitiesBeforeDaysRetainedDataProvider(): array
    {
        return [
            'all entities should be removed' => [
                [
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('-15 days')],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('-7 days')],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('-9 days')],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('-13 days')],
                ],
                5,
                0,
            ],
            'no entities should be removed' => [
                [
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('-3 days')],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('-5 days')],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('-7 days')],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('-9 days')],
                ],
                10,
                4,
            ],
            'some entities should be removed' => [
                [
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('-3 days')],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('-2 days')],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('+1 days')],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('+2 days')],
                ],
                1,
                3,
            ],
        ];
    }
}
