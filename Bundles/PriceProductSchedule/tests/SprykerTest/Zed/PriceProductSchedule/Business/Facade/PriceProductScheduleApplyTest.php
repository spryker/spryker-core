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
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $defaultProductTransfer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->priceProductScheduleFacade = $this->tester->getFacade();
        $this->priceProductFacade = $this->tester->getLocator()->priceProduct()->facade();
        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
        $this->spyPriceProductScheduleQuery = $this->tester->getPriceProductScheduleQuery();
        $this->defaultProductTransfer = $this->tester->haveProduct();
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
            $priceProductDefaultData = [
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::PRICE_TYPE => [
                        PriceTypeTransfer::NAME => $this->priceProductFacade->getDefaultPriceTypeName(),
                        PriceTypeTransfer::ID_PRICE_TYPE => $this->tester->getPriceTypeId($this->priceProductFacade->getDefaultPriceTypeName()),
                    ],
                ],
            ];

            $priceProductData = array_merge(
                $priceProductDefaultData,
                $productScheduleTestData[static::KEY_PRICE_PRODUCT_SCHEDULE_DATA]
            );

            if ($productScheduleTestData[static::KEY_IS_OTHER_PRODUCT_CONCRETE]) {
                $otherProduct = $this->tester->haveProduct();
                $priceProductData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT] = $otherProduct->getIdProductConcrete();
            }

            if ($productScheduleTestData[static::KEY_IS_OTHER_PRODUCT_ABSTRACT]) {
                $otherProductAbstract = $this->tester->haveProductAbstract();
                $priceProductData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT_ABSTRACT] = $otherProductAbstract->getIdProductAbstract();
            }

            if ($productScheduleTestData[static::KEY_IS_OTHER_PRODUCT_CONCRETE] === false
                && $productScheduleTestData[static::KEY_IS_OTHER_PRODUCT_ABSTRACT] === false) {
                $priceProductData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT] = $this->defaultProductTransfer->getIdProductConcrete();
            }

            if ($productScheduleTestData[static::KEY_IS_OTHER_PRICE_TYPE]) {
                $otherPriceType = $this->tester->havePriceType('ORIGINAL');
                $priceProductData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::PRICE_TYPE] = [
                    PriceTypeTransfer::NAME => $otherPriceType->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $otherPriceType->getIdPriceType(),
                ];
            }

            if ($productScheduleTestData[static::KEY_IS_OTHER_CURRENCY]) {
                $otherCurrency = $this->currencyFacade->fromIsoCode('CHF');
                $priceProductData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE] = [
                    MoneyValueTransfer::FK_CURRENCY => $otherCurrency->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $otherCurrency,
                ];
            }

            $this->tester->havePriceProductSchedule($priceProductData);
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

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $this->defaultProductTransfer->getIdProductConcrete(),
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $otherStore->getIdStore(),
                ],
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $this->priceProductFacade->getDefaultPriceTypeName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $this->tester->getPriceTypeId($this->priceProductFacade->getDefaultPriceTypeName()),
                ],
            ],
        ]);

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity->isCurrent(), 'Scheduled price for other store should not have been set as current.');
    }

    /**
     * @return array
     */
    public function differentPriceProductScheduleShouldApplyDataProvider(): array
    {
        return [
            'single price product schedule' => [
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
            ],
            'price product schedule with different concrete products' => [
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
            ],
            'price product schedule with different concrete products, price types and currencies' => [
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
            ],
            'price product schedule with different abstract products' => [
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
            ],
            'price product schedule with different product types' => [
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
            ],
            'price product schedule with different currencies' => [
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
            ],
        ];
    }
}
