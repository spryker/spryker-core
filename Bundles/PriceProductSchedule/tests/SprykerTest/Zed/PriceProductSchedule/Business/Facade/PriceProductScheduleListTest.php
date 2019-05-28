<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\PriceProductScheduleListBuilder;
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
        $priceProductScheduleEntity = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse(
            $priceProductScheduleEntity->isCurrent(),
            'Scheduled price with not active price product schedule list should not have been set as current.'
        );
    }

    /**
     * @dataProvider createPriceProductScheduleListDataProvider
     *
     * @param array $priceProductScheduleListData
     *
     * @return void
     */
    public function testCreatePriceProductScheduleList(array $priceProductScheduleListData): void
    {
        // Assign
        $priceProductScheduleListTransfer = (new PriceProductScheduleListBuilder($priceProductScheduleListData))
            ->build();

        // Act
        $priceProductScheduleListResponseTransfer = $this->priceProductScheduleFacade
            ->createPriceProductScheduleList($priceProductScheduleListTransfer);

        // Assert
        $this->assertTrue(
            $priceProductScheduleListResponseTransfer->getIsSuccess(),
            'Price Product Schedule list should be created.'
        );
    }

    /**
     * @dataProvider createPriceProductScheduleListDataProvider
     *
     * @param array $priceProductScheduleListData
     *
     * @return void
     */
    public function testUpdatePriceProductScheduleList(array $priceProductScheduleListData): void
    {
        // Assign
        $priceProductScheduleListTransfer = $this->tester->havePriceProductScheduleList();

        if (isset($priceProductScheduleListData[PriceProductScheduleListTransfer::FK_USER])) {
            $priceProductScheduleListTransfer->setFkUser(
                $priceProductScheduleListData[PriceProductScheduleListTransfer::FK_USER]
            );
        }

        if (isset($priceProductScheduleListData[PriceProductScheduleListTransfer::NAME])) {
            $priceProductScheduleListTransfer->setName(
                $priceProductScheduleListData[PriceProductScheduleListTransfer::NAME]
            );
        }

        if (isset($priceProductScheduleListData[PriceProductScheduleListTransfer::IS_ACTIVE])) {
            $priceProductScheduleListTransfer->setIsActive(
                $priceProductScheduleListData[PriceProductScheduleListTransfer::IS_ACTIVE]
            );
        }

        // Act
        $priceProductScheduleListResponseTransfer = $this->priceProductScheduleFacade
            ->updatePriceProductScheduleList($priceProductScheduleListTransfer);

        // Assert
        $this->assertTrue(
            $priceProductScheduleListResponseTransfer->getIsSuccess(),
            'Price Product Schedule list should be updated.'
        );

        $this->assertEquals(
            $priceProductScheduleListResponseTransfer->getPriceProductScheduleList(),
            $priceProductScheduleListTransfer,
            'Values must be updated'
        );
    }

    /**
     * @return array
     */
    public function createPriceProductScheduleListDataProvider(): array
    {
        return [
            'price product schedule lists should be created' => [
                [
                    [
                        PriceProductScheduleListTransfer::IS_ACTIVE => true,
                        PriceProductScheduleListTransfer::NAME => 'FOO',
                        PriceProductScheduleListTransfer::FK_USER => 1,
                    ],
                    [
                        PriceProductScheduleListTransfer::IS_ACTIVE => false,
                        PriceProductScheduleListTransfer::NAME => 'TEST',
                        PriceProductScheduleListTransfer::FK_USER => 1,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function updatePriceProductScheduleListDataProvider(): array
    {
        return [
            'price product schedule lists should be updated' => [
                [
                    [
                        PriceProductScheduleListTransfer::IS_ACTIVE => false,
                    ],
                    [
                        PriceProductScheduleListTransfer::NAME => 'FOO',
                    ],
                    [
                        PriceProductScheduleListTransfer::FK_USER => 2,
                    ],
                ],
            ],
        ];
    }
}
