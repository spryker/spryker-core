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
 * @group PriceProductScheduleApplyTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleApplyTest extends Unit
{
    public const KEY_IS_OTHER_CURRENCY = 'isOtherCurrency';
    public const KEY_IS_OTHER_PRICE_TYPE = 'isOtherPriceType';
    public const KEY_IS_PRODUCT_CONCRETE = 'isProductConcrete';
    public const KEY_IS_PRODUCT_ABSTRACT = 'isProductAbstract';
    public const KEY_PRICE_PRODUCT_SCHEDULE_DATA = 'priceProductScheduleData';

    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface
     */
    protected $priceProductScheduleFacade;

    /**
     * @var \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected $currencyFacade;

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
        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
        $this->storeFacade = $this->tester->getLocator()->store()->facade();
    }

    /**
     * @param array $priceProductScheduleTestData
     *
     * @return void
     */
    public function testPriceProductScheduleWithDifferentConcreteProductsPriceTypesAndCurrenciesShouldApply(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $priceTypeTransfer = $this->tester->havePriceType();
        $currencyId = $this->tester->haveCurrency();

        $priceProductScheduleData1 = [
            PriceProductScheduleTransfer::PRICE_PRODUCT => $this->getPriceProductData(),
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-10 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+5 days')),
        ];

        $priceProductScheduleData1[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT_ABSTRACT] = $productAbstractTransfer->getIdProductAbstract();
        $priceProductScheduleData1[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::FK_CURRENCY] = $currencyId;

        $this->tester->havePriceProductSchedule($priceProductScheduleData1);

        $priceProductScheduleData2 = [
            PriceProductScheduleTransfer::PRICE_PRODUCT => $this->getPriceProductData(),
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-12 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+6 days')),
        ];

        $priceProductScheduleData2[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT_ABSTRACT] = $productAbstractTransfer->getIdProductAbstract();

        $this->tester->havePriceProductSchedule($priceProductScheduleData2);

        $priceProductScheduleData3 = [
            PriceProductScheduleTransfer::PRICE_PRODUCT => $this->getPriceProductData(),
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-2 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 days')),
        ];

        $priceProductScheduleData3[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT_ABSTRACT] = $productAbstractTransfer->getIdProductAbstract();
        $priceProductScheduleData3[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::PRICE_TYPE] = [
            PriceTypeTransfer::NAME => $priceTypeTransfer->getName(),
            PriceTypeTransfer::ID_PRICE_TYPE => $priceTypeTransfer->getIdPriceType(),
        ];

        $this->tester->havePriceProductSchedule($priceProductScheduleData3);

        $priceProductScheduleData4 = [
            PriceProductScheduleTransfer::PRICE_PRODUCT => $this->getPriceProductData(),
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-3 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+9 days')),
        ];

        $priceProductScheduleData4[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT] = $productConcreteTransfer->getIdProductConcrete();
        $priceProductScheduleData4[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::PRICE_TYPE] = [
            PriceTypeTransfer::NAME => $priceTypeTransfer->getName(),
            PriceTypeTransfer::ID_PRICE_TYPE => $priceTypeTransfer->getIdPriceType(),
        ];

        $this->tester->havePriceProductSchedule($priceProductScheduleData4);

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntities = $this->tester->getPriceProductScheduleQuery()->find();

        foreach ($priceProductScheduleEntities as $priceProductScheduleEntity) {
            $this->assertTrue(
                $priceProductScheduleEntity->isCurrent(),
                sprintf(
                    'Scheduled price with id: #%s, active from: %s, active to: %s should have been set as current.',
                    $priceProductScheduleEntity->getIdPriceProductSchedule(),
                    $priceProductScheduleEntity->getActiveFrom()->format('d-m-Y'),
                    $priceProductScheduleEntity->getActiveTo()->format('d-m-Y')
                )
            );
        }
    }

    /**
     * @param array $priceProductScheduleTestData
     *
     * @return void
     */
    public function testPriceProductScheduleWithBiggerPricesAndLowerDateRangeShouldApply(): void
    {
        // Assign
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $currencyId = $this->tester->haveCurrency();
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $priceType = $this->tester->havePriceType();

        $priceProductScheduleForApplyData = [
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $priceType->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $priceType->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::GROSS_AMOUNT => 250000,
                    MoneyValueTransfer::NET_AMOUNT => 150000,
                ],
            ],
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-10 min')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+10 min')),
        ];

        $priceProductScheduleForApplyTransfer = $this->tester->havePriceProductSchedule($priceProductScheduleForApplyData);

        $priceProductScheduleNotApplyData = [
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $priceType->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $priceType->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::GROSS_AMOUNT => 2500,
                    MoneyValueTransfer::NET_AMOUNT => 1500,
                ],
            ],
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-15 min')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+15 min')),
        ];

        $priceProductScheduleNotApplyTransfer = $this->tester->havePriceProductSchedule($priceProductScheduleNotApplyData);

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $appliedPriceProductScheduleEntity = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule(
            $priceProductScheduleForApplyTransfer->getIdPriceProductSchedule()
        );

        $this->assertTrue(
            $appliedPriceProductScheduleEntity->isCurrent(),
            sprintf(
                'Scheduled price with id: #%s, active from: %s, active to: %s, gross price: %s, net price: %s should have been set as current.',
                $appliedPriceProductScheduleEntity->getIdPriceProductSchedule(),
                $appliedPriceProductScheduleEntity->getActiveFrom()->format('d-m-Y'),
                $appliedPriceProductScheduleEntity->getActiveTo()->format('d-m-Y'),
                $appliedPriceProductScheduleEntity->getGrossPrice(),
                $appliedPriceProductScheduleEntity->getNetPrice()
            )
        );

        $notAppliedPriceProductScheduleEntity = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule(
            $priceProductScheduleNotApplyTransfer->getIdPriceProductSchedule()
        );

        $this->assertFalse(
            $notAppliedPriceProductScheduleEntity->isCurrent(),
            sprintf(
                'Scheduled price with id: #%s, active from: %s, active to: %s, gross price: %s, net price: %s should have not been set as current.',
                $notAppliedPriceProductScheduleEntity->getIdPriceProductSchedule(),
                $notAppliedPriceProductScheduleEntity->getActiveFrom()->format('d-m-Y'),
                $notAppliedPriceProductScheduleEntity->getActiveTo()->format('d-m-Y'),
                $notAppliedPriceProductScheduleEntity->getGrossPrice(),
                $notAppliedPriceProductScheduleEntity->getNetPrice()
            )
        );
    }

    /**
     * @dataProvider activePriceProductSchedulesDataProvider
     *
     * @param array $priceProductScheduleTestData
     *
     * @return void
     */
    public function testPriceProductScheduleForDifferentConcreteProductsShouldApply(
        array $priceProductScheduleTestData = []
    ): void {
        // Assign
        foreach ($priceProductScheduleTestData as $productScheduleTestData) {
            $priceProductScheduleData = $productScheduleTestData[static::KEY_PRICE_PRODUCT_SCHEDULE_DATA];
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT] = $this->getPriceProductData();

            $productConcreteTransfer = $this->tester->haveProduct();
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT] = $productConcreteTransfer->getIdProductConcrete();

            $this->tester->havePriceProductSchedule($priceProductScheduleData);
        }

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntities = $this->tester->getPriceProductScheduleQuery()->find();

        foreach ($priceProductScheduleEntities as $priceProductScheduleEntity) {
            $this->assertTrue(
                $priceProductScheduleEntity->isCurrent(),
                sprintf(
                    'Scheduled price with id: #%s, active from: %s, active to: %s should have been set as current.',
                    $priceProductScheduleEntity->getIdPriceProductSchedule(),
                    $priceProductScheduleEntity->getActiveFrom()->format('d-m-Y'),
                    $priceProductScheduleEntity->getActiveTo()->format('d-m-Y')
                )
            );
        }
    }

    /**
     * @dataProvider activePriceProductSchedulesDataProvider
     *
     * @param array $priceProductScheduleTestData
     *
     * @return void
     */
    public function testPriceProductScheduleForDifferentAbstractProductsShouldApply(
        array $priceProductScheduleTestData = []
    ): void {
        // Assign
        foreach ($priceProductScheduleTestData as $productScheduleTestData) {
            $priceProductScheduleData = $productScheduleTestData[static::KEY_PRICE_PRODUCT_SCHEDULE_DATA];
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT] = $this->getPriceProductData();

            $productAbstract = $this->tester->haveProductAbstract();
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT_ABSTRACT] = $productAbstract->getIdProductAbstract();

            $this->tester->havePriceProductSchedule($priceProductScheduleData);
        }

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntities = $this->tester->getPriceProductScheduleQuery()->find();

        foreach ($priceProductScheduleEntities as $priceProductScheduleEntity) {
            $this->assertTrue(
                $priceProductScheduleEntity->isCurrent(),
                sprintf(
                    'Scheduled price with id: #%s, active from: %s, active to: %s should have been set as current.',
                    $priceProductScheduleEntity->getIdPriceProductSchedule(),
                    $priceProductScheduleEntity->getActiveFrom()->format('d-m-Y'),
                    $priceProductScheduleEntity->getActiveTo()->format('d-m-Y')
                )
            );
        }
    }

    /**
     * @dataProvider activePriceProductSchedulesDataProvider
     *
     * @param array $priceProductScheduleTestData
     *
     * @return void
     */
    public function testPriceProductScheduleForDifferentCurrenciesShouldApply(
        array $priceProductScheduleTestData = []
    ): void {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();

        foreach ($priceProductScheduleTestData as $productScheduleTestData) {
            $priceProductScheduleData = $productScheduleTestData[static::KEY_PRICE_PRODUCT_SCHEDULE_DATA];
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT] = $this->getPriceProductData();
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT] = $productConcreteTransfer->getIdProductConcrete();

            $otherCurrencyId = $this->tester->haveCurrency();
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::FK_CURRENCY] = $otherCurrencyId;

            $this->tester->havePriceProductSchedule($priceProductScheduleData);
        }

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntities = $this->tester->getPriceProductScheduleQuery()->find();

        foreach ($priceProductScheduleEntities as $priceProductScheduleEntity) {
            $this->assertTrue(
                $priceProductScheduleEntity->isCurrent(),
                sprintf(
                    'Scheduled price with id: #%s, active from: %s, active to: %s should have been set as current.',
                    $priceProductScheduleEntity->getIdPriceProductSchedule(),
                    $priceProductScheduleEntity->getActiveFrom()->format('d-m-Y'),
                    $priceProductScheduleEntity->getActiveTo()->format('d-m-Y')
                )
            );
        }
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleInTheDifferentStoreShouldNotApply(): void
    {
        // Assign
        $otherStore = $this->tester->haveStore();
        $currencyId = $this->tester->haveCurrency();
        $priceType = $this->tester->havePriceType();
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $otherStore->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                ],
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $priceType->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $priceType->getIdPriceType(),
                ],
            ],
        ]);

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse(
            $priceProductScheduleEntity->isCurrent(),
            'Scheduled price for other store should not have been set as current.'
        );
    }

    /**
     * @return array
     */
    protected function getPriceProductData(): array
    {
        $currencyId = $this->tester->haveCurrency();
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $priceType = $this->tester->havePriceType();

        return [
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
    public function activePriceProductSchedulesDataProvider(): array
    {
        return [
            'active price product schedules' => [
                [
                    [
                        static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-10 days')),
                            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+5 days')),
                        ],
                    ],
                    [
                        static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-3 days')),
                            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 days')),
                        ],
                    ],
                    [
                        static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-8 days')),
                            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+2 days')),
                        ],
                    ],
                ],
            ],
        ];
    }
}
