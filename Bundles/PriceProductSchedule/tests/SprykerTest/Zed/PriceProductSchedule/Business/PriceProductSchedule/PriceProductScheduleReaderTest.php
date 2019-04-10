<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSchedule
 * @group Business
 * @group PriceProductSchedule
 * @group PriceProductScheduleReaderTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleReaderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory
     */
    protected $priceProductScheduleFactory;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleReaderInterface
     */
    protected $priceProductScheduleReader;

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

        $this->priceProductScheduleFactory = new PriceProductScheduleBusinessFactory();
        $this->priceProductScheduleReader = $this->priceProductScheduleFactory->createPriceProductScheduleReader();
        $this->storeFacade = $this->tester->getLocator()->store()->facade();
    }

    /**
     * @dataProvider findPriceProductSchedulesToDisableDataProvider
     *
     * @param array $data
     * @param int $expectedCount
     *
     * @return void
     */
    public function testFindPriceProductSchedulesToDisable(array $data, int $expectedCount): void
    {
        // Assign
        foreach ($data as $priceProductScheduleData) {
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT] = $this->getPriceProductData();

            $this->tester->havePriceProductSchedule($priceProductScheduleData);
        }

        // Act
        $priceProductSchedulesToDisable = $this->priceProductScheduleReader->findPriceProductSchedulesToDisable();

        // Assert
        $this->assertEquals($expectedCount, count($priceProductSchedulesToDisable));
    }

    /**
     * @dataProvider findPriceProductSchedulesToEnableDataProvider
     *
     * @param array $data
     * @param int $expectedCount
     *
     * @return void
     */
    public function testFindPriceProductSchedulesToEnable(array $data, int $expectedCount): void
    {
        // Assign
        foreach ($data as $priceProductScheduleData) {
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT] = $this->getPriceProductData();

            $this->tester->havePriceProductSchedule($priceProductScheduleData);
        }

        // Act
        $priceProductSchedulesToEnable = $this->priceProductScheduleReader->findPriceProductSchedulesToEnableForCurrentStore();

        // Assert
        $this->assertEquals($expectedCount, count($priceProductSchedulesToEnable));
    }

    /**
     * @dataProvider findSimilarPriceProductSchedulesToDisableDataProvider
     *
     * @param array $activePriceProductScheduleData
     * @param array $data
     * @param int $expectedCount
     *
     * @return void
     */
    public function testFindSimilarPriceProductSchedulesToDisable(array $activePriceProductScheduleData, array $data, int $expectedCount): void
    {
        // Assign
        $priceProductData = $this->getPriceProductData();
        $activePriceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT] = $priceProductData;
        $activePriceProductSchedule = $this->tester->havePriceProductSchedule($activePriceProductScheduleData);

        foreach ($data as $priceProductScheduleData) {
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT] = $priceProductData;

            $this->tester->havePriceProductSchedule($priceProductScheduleData);
        }

        // Act
        $similarPriceProductSchedulesToDisable = $this->priceProductScheduleReader->findSimilarPriceProductSchedulesToDisable($activePriceProductSchedule);

        // Assert
        $this->assertEquals($expectedCount, count($similarPriceProductSchedulesToDisable));
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
    public function findPriceProductSchedulesToDisableDataProvider(): array
    {
        return [
            'find price product schedules to disable should find expected count' => [
                [
                    [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-15 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-5 days')),
                        PriceProductScheduleTransfer::IS_CURRENT => true,
                    ],
                    [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-2 hours')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-1 hours')),
                        PriceProductScheduleTransfer::IS_CURRENT => true,
                    ],
                    [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('+5 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+10 days')),
                        PriceProductScheduleTransfer::IS_CURRENT => true,
                    ],
                    [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('+15 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-13 days')),
                        PriceProductScheduleTransfer::IS_CURRENT => true,
                    ],
                    [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-15 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-13 days')),
                        PriceProductScheduleTransfer::IS_CURRENT => false,
                    ],
                ],
                3,
            ],
        ];
    }

    /**
     * @return array
     */
    public function findPriceProductSchedulesToEnableDataProvider(): array
    {
        return [
            'find price product schedules to enable should find expected count' => [
                [
                    [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-2 hours')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-1 hours')),
                        PriceProductScheduleTransfer::IS_CURRENT => false,
                    ],
                    [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('+5 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+10 days')),
                        PriceProductScheduleTransfer::IS_CURRENT => false,
                    ],
                    [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('+15 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-13 days')),
                        PriceProductScheduleTransfer::IS_CURRENT => true,
                    ],
                    [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-2 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+4 days')),
                        PriceProductScheduleTransfer::IS_CURRENT => false,
                    ],
                ],
                1,
            ],
        ];
    }

    /**
     * @return array
     */
    public function findSimilarPriceProductSchedulesToDisableDataProvider(): array
    {
        return [
            'find similar price product schedules to enable should find expected count' => [
                [
                    PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-1 hours')),
                    PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hours')),
                    PriceProductScheduleTransfer::IS_CURRENT => true,
                ],
                [
                    [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-2 hours')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-2 hours')),
                        PriceProductScheduleTransfer::IS_CURRENT => true,
                    ],
                    [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-1 day')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 day')),
                        PriceProductScheduleTransfer::IS_CURRENT => true,
                    ],
                ],
                2,
            ],
        ];
    }
}
