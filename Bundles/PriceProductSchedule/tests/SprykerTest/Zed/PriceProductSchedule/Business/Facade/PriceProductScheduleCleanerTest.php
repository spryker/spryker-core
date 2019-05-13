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
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface
     */
    protected $priceProductScheduleFacade;

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
     * @dataProvider priceProductScheduleCleanerShouldRemoveAllEntitiesBeforeDaysRetainedDataProvider
     *
     * @param array $data
     * @param int $daysRetained
     * @param int $expectedCount
     *
     * @return void
     */
    public function testPriceProductScheduleCleanerShouldRemoveAllEntitiesBeforeDaysRetained(
        array $data,
        int $daysRetained,
        int $expectedCount
    ): void {
        // Assign
        foreach ($data as $priceProductScheduleData) {
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT] = $this->getPriceProductData();
            $priceProductScheduleData[PriceProductScheduleTransfer::ACTIVE_FROM] = (new DateTime('-30 days'));

            $this->tester->havePriceProductSchedule($priceProductScheduleData);
        }

        // Act
        $this->priceProductScheduleFacade->cleanAppliedScheduledPrices($daysRetained);

        // Assert
        $this->assertEquals($expectedCount, $this->tester->getPriceProductScheduleQuery()->find()->count());
    }

    /**
     * @return array
     */
    protected function getPriceProductData(): array
    {
        $currencyId = $this->tester->haveCurrency();
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $priceType = $this->tester->havePriceType();
        $productConcreteTransfer = $this->tester->haveProduct();

        return [
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::PRICE_TYPE => [
                PriceTypeTransfer::NAME => $priceType->getName(),
                PriceTypeTransfer::ID_PRICE_TYPE => $priceType->getIdPriceType(),
            ],
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                MoneyValueTransfer::FK_CURRENCY => $currencyId,
            ],
        ];
    }

    /**
     * @return array
     */
    public function priceProductScheduleCleanerShouldRemoveAllEntitiesBeforeDaysRetainedDataProvider(): array
    {
        return [
            'all entities should be removed' => [
                [
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-15 days'))],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-9 days'))],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-6 days'))],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-13 days'))],
                ],
                5,
                0,
            ],
            'no entities should be removed' => [
                [
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-3 days'))],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-5 days'))],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-7 days'))],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-9 days'))],
                ],
                10,
                4,
            ],
            'some entities should be removed' => [
                [
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-3 days'))],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-2 days'))],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 days'))],
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+2 days'))],
                ],
                1,
                3,
            ],
            'active entities should not be removed' => [
                [
                    [PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+5 days'))],
                    [
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-3 days')),
                        PriceProductScheduleTransfer::IS_CURRENT => true,

                    ],
                    [
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-11 days')),
                        PriceProductScheduleTransfer::IS_CURRENT => true,
                    ],
                ],
                10,
                3,
            ],
        ];
    }
}
