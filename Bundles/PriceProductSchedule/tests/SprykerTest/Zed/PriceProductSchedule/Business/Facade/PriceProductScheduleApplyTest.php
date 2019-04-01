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
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->priceProductScheduleFacade = $this->tester->getFacade();
        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
        $this->spyPriceProductScheduleQuery = $this->tester->getPriceProductScheduleQuery();
    }

    /**
     * @return void
     */
    public function testActivePriceProductScheduleShouldApply(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ],
        ]);

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent(), 'Scheduled price with active date range should have been set as current.');
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleInTheDifferentStoreShouldNotApply(): void
    {
        // Assign
        $otherStore = $this->tester->haveStore();
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $otherStore->getIdStore(),
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
     * @return void
     */
    public function testPriceProductScheduleShouldApplyForDifferentProducts(): void
    {
        // Assign
        $productConcreteTransfer1 = $this->tester->haveProduct();

        $priceProductScheduleTransfer1 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer1->getIdProductConcrete(),
            ],
        ]);

        $productConcreteTransfer2 = $this->tester->haveProduct();

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer2->getIdProductConcrete(),
            ],
        ]);

        $productConcreteTransfer3 = $this->tester->haveProduct();

        $priceProductScheduleTransfer3 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer3->getIdProductConcrete(),
            ],
        ]);

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity1 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer1->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity1->isCurrent(), 'Scheduled price with active date range for product #1 should have been set as current.');

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity2->isCurrent(), 'Scheduled price with active date range for product #2 should have been set as current.');

        $priceProductScheduleEntity3 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer3->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity3->isCurrent(), 'Scheduled price with active date range for product #3 should have been set as current.');
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleWithDifferentProductsCurrenciesAndPriceTypesShouldApply(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer1 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
            ],
        ]);

        $otherCurrency = $this->currencyFacade->fromIsoCode('CHF');

        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_CURRENCY => $otherCurrency->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $otherCurrency,
                ],
            ],
        ]);

        $productConcreteTransfer3 = $this->tester->haveProduct();

        $priceProductScheduleTransfer3 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer3->getFkProductAbstract(),
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_CURRENCY => $otherCurrency->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $otherCurrency,
                ],
            ],
        ]);

        $productConcreteTransfer4 = $this->tester->haveProduct();

        $priceProductScheduleTransfer4 = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer4->getIdProductConcrete(),
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_CURRENCY => $otherCurrency->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $otherCurrency,
                ],
            ],
        ]);

        // Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity1 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer1->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity1->isCurrent(), 'Scheduled price with active date range for product #1 should have been set as current.');

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity2->isCurrent(), 'Scheduled price with active date range and other currency for product #1 should have been set as current.');

        $priceProductScheduleEntity3 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer3->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity3->isCurrent(), 'Scheduled price with active date range and other currency for product #2 should have been set as current.');

        $priceProductScheduleEntity4 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer4->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity4->isCurrent(), 'Scheduled price with active date range and other currency for product #3 should have been set as current.');
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleShouldApplyForDifferentAbstractProducts(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                ],
            ]
        );

        $productConcreteTransfer2 = $this->tester->haveProduct();
        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer2->getFkProductAbstract(),
                ],
            ]
        );

        //Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        //Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent(), 'Scheduled price with active date range for abstract product #1 should have been set as current.');

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity2->isCurrent(), 'Scheduled price with active date range for abstract product #2 should have been set as current.');
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleShouldApplyForDifferentConcreteProducts(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        $productConcreteTransfer2 = $this->tester->haveProduct();
        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer2->getIdProductConcrete(),
                ],
            ]
        );

        //Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        //Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent(), 'Scheduled price with active date range for product #1 should have been set as current.');

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity2->isCurrent(), 'Scheduled price with active date range for product #2 should have been set as current.');
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleShouldApplyForDifferentProductTypes(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                ],
            ]
        );

        $priceTypeOriginal = 'ORIGINAL';
        $productConcreteTransfer2 = $this->tester->haveProduct();
        $priceProductScheduleTransfer2 = $this->tester->havePriceProductSchedule(
            [
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer2->getIdProductConcrete(),
                    PriceProductTransfer::PRICE_TYPE => [
                        PriceTypeTransfer::NAME => $priceTypeOriginal,
                        PriceTypeTransfer::ID_PRICE_TYPE => $this->tester->getPriceTypeId($priceTypeOriginal),
                    ],
                ],
            ]
        );

        //Act
        $this->priceProductScheduleFacade->applyScheduledPrices();

        //Assert
        $priceProductScheduleEntity = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity->isCurrent(), 'Scheduled price with active date range and price type #1 for product #1 should have been set as current.');

        $priceProductScheduleEntity2 = $this->spyPriceProductScheduleQuery->findOneByIdPriceProductSchedule($priceProductScheduleTransfer2->getIdPriceProductSchedule());
        $this->assertTrue($priceProductScheduleEntity2->isCurrent(), 'Scheduled price with active date range and price type #2 for product #2 should have been set as current.');
    }
}
