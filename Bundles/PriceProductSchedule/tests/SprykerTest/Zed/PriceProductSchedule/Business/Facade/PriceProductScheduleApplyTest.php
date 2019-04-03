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
    public const KEY_IS_OTHER_PRODUCT_CONCRETE = 'isOtherProductConcrete';
    public const KEY_IS_OTHER_PRODUCT_ABSTRACT = 'isOtherProductAbstract';
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
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected $spyPriceProductScheduleQuery;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $defaultProductTransfer;

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
        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
        $this->spyPriceProductScheduleQuery = $this->tester->getPriceProductScheduleQuery();
        $this->defaultProductTransfer = $this->tester->haveProduct();
        $this->storeFacade = $this->tester->getLocator()->store()->facade();
    }

    /**
     * @dataProvider differentPriceProductScheduleShouldApplyDataProvider
     *
     * @param array $priceProductScheduleTestData
     *
     * @return void
     */
    public function testDifferentPriceProductScheduleShouldApply(array $priceProductScheduleTestData = []): void
    {
        // Assign
        foreach ($priceProductScheduleTestData as $productScheduleTestData) {
            $priceProductScheduleData = $productScheduleTestData[static::KEY_PRICE_PRODUCT_SCHEDULE_DATA];
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT] = $this->getPriceProductData();

            if ($productScheduleTestData[static::KEY_IS_OTHER_PRODUCT_CONCRETE]) {
                $otherProduct = $this->tester->haveProduct();
                $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT] = $otherProduct->getIdProductConcrete();
            }

            if ($productScheduleTestData[static::KEY_IS_OTHER_PRODUCT_ABSTRACT]) {
                $otherProductAbstract = $this->tester->haveProductAbstract();
                $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT_ABSTRACT] = $otherProductAbstract->getIdProductAbstract();
            }

            if ($productScheduleTestData[static::KEY_IS_OTHER_PRICE_TYPE]) {
                $otherPriceType = $this->tester->havePriceType();
                $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::PRICE_TYPE] = [
                    PriceTypeTransfer::NAME => $otherPriceType->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $otherPriceType->getIdPriceType(),
                ];
            }

            if ($productScheduleTestData[static::KEY_IS_OTHER_CURRENCY]) {
                $otherCurrency = $this->tester->haveCurrency();
                $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE] = [
                    MoneyValueTransfer::FK_CURRENCY => $otherCurrency->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $otherCurrency,
                ];
            }

            $this->tester->havePriceProductSchedule($priceProductScheduleData);
        }

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntities = $this->spyPriceProductScheduleQuery->find();

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

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $this->defaultProductTransfer->getIdProductConcrete(),
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $otherStore->getIdStore(),
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                    MoneyValueTransfer::NET_AMOUNT => 200,
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
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
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
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 120,
            ],
        ];
    }

    /**
     * @return array
     */
    public function differentPriceProductScheduleShouldApplyDataProvider(): array
    {
        return [
            'single price product schedule' => $this->getSinglePriceProductScheduleData(),
            'price product schedule with different concrete products' => $this->getPriceProductScheduleWithDifferentConcreteProductsData(),
            'price product schedule with different concrete products, price types and currencies' => $this->getPriceProductScheduleWithDifferentConcreteProductsPriceTypesAndCurrenciesData(),
            'price product schedule with different abstract products' => $this->getPriceProductScheduleWithDifferentAbstractProductsData(),
            'price product schedule with different product types' => $this->getPriceProductScheduleWithDifferentProductTypesData(),
            'price product schedule with different currencies' => $this->getPriceProductScheduleWithDifferentCurrenciesData(),
            ];
    }

    /**
     * @return array
     */
    protected function getSinglePriceProductScheduleData(): array
    {
        return [
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => false,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => false,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => false,
                    static::KEY_IS_OTHER_CURRENCY => false,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-5 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 days')),
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getPriceProductScheduleWithDifferentConcreteProductsData(): array
    {
        return [
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => false,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => true,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => false,
                    static::KEY_IS_OTHER_CURRENCY => false,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-10 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+5 days')),
                    ],
                ],
            ],
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => false,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => true,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => false,
                    static::KEY_IS_OTHER_CURRENCY => false,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-3 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 days')),
                    ],
                ],
            ],
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => false,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => true,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => false,
                    static::KEY_IS_OTHER_CURRENCY => false,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-8 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+2 days')),
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getPriceProductScheduleWithDifferentConcreteProductsPriceTypesAndCurrenciesData(): array
    {
        return [
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => false,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => true,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => false,
                    static::KEY_IS_OTHER_CURRENCY => false,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-10 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+5 days')),
                    ],
                ],
            ],
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => false,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => false,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => true,
                    static::KEY_IS_OTHER_CURRENCY => true,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-3 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 days')),
                    ],
                ],
            ],
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => false,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => true,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => false,
                    static::KEY_IS_OTHER_CURRENCY => true,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-8 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+2 days')),
                    ],
                ],
            ],
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => true,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => true,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => false,
                    static::KEY_IS_OTHER_CURRENCY => true,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-12 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+9 days')),
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getPriceProductScheduleWithDifferentAbstractProductsData(): array
    {
        return [
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => false,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => false,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => true,
                    static::KEY_IS_OTHER_CURRENCY => false,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-10 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+5 days')),
                    ],
                ],
            ],
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => false,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => false,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => true,
                    static::KEY_IS_OTHER_CURRENCY => false,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-3 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 days')),
                    ],
                ],
            ],
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => false,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => false,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => true,
                    static::KEY_IS_OTHER_CURRENCY => false,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-8 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+2 days')),
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getPriceProductScheduleWithDifferentProductTypesData(): array
    {
        return [
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => true,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => false,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => false,
                    static::KEY_IS_OTHER_CURRENCY => false,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-10 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+5 days')),
                    ],
                ],
            ],
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => true,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => false,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => false,
                    static::KEY_IS_OTHER_CURRENCY => false,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-3 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 days')),
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getPriceProductScheduleWithDifferentCurrenciesData(): array
    {
        return [
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => false,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => false,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => false,
                    static::KEY_IS_OTHER_CURRENCY => false,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-10 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+5 days')),
                    ],
                ],
            ],
            [
                [
                    static::KEY_IS_OTHER_PRICE_TYPE => false,
                    static::KEY_IS_OTHER_PRODUCT_CONCRETE => false,
                    static::KEY_IS_OTHER_PRODUCT_ABSTRACT => false,
                    static::KEY_IS_OTHER_CURRENCY => true,
                    static::KEY_PRICE_PRODUCT_SCHEDULE_DATA => [
                        PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-3 days')),
                        PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 days')),
                    ],
                ],
            ],
        ];
    }
}
