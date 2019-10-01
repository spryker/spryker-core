<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Applier\AbstractProductPriceProductScheduleApplier;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Applier\ConcreteProductPriceProductScheduleApplier;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeBridge;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeBridge;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManager;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepository;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSchedule
 * @group Business
 * @group Facade
 * @group RemovePriceProductScheduleListTest
 * Add your own group annotations below this line
 */
class RemovePriceProductScheduleListTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->tester->ensureDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testRemovePriceProductScheduleListWithActiveAbstractPricesShouldExecuteApply(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();
        $storeTransfer = $this->tester->haveStore();

        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => 'test1']);

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $defaultPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $fallbackPriceTypeTransfer = $this->tester->havePriceType();

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $fallbackPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 200,
                MoneyValueTransfer::GROSS_AMOUNT => 200,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);
        $priceProductScheduleList = $this->tester->havePriceProductScheduleList([
            PriceProductScheduleListTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleList,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleList,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $applierMock = $this->getMockBuilder(AbstractProductPriceProductScheduleApplier::class)
            ->disableOriginalConstructor()
            ->setMethods(['applyScheduledPrices'])
            ->getMock();

        $applierMock->expects($this->once())->method('applyScheduledPrices');
        $priceProductScheduleFacade = $this->getFacadeMockForAbstractPrices($applierMock);

        // Act
        $priceProductScheduleFacade
            ->removePriceProductScheduleList($priceProductScheduleList->getIdPriceProductScheduleList());

        $priceProductScheduleListCollection = $this->tester
            ->getPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($priceProductScheduleList->getIdPriceProductScheduleList())
            ->find()
            ->getData();

        // Assert
        $this->assertCount(
            0,
            $priceProductScheduleListCollection,
            'Count of scheduled price lists does not match expected value'
        );
    }

    /**
     * @return void
     */
    public function testRemovePriceProductScheduleListWithActiveConcretePricesShouldExecuteApply(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();
        $storeTransfer = $this->tester->haveStore();

        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => 'test1']);

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::PRICE_TYPE => $defaultPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $fallbackPriceTypeTransfer = $this->tester->havePriceType();

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::PRICE_TYPE => $fallbackPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 200,
                MoneyValueTransfer::GROSS_AMOUNT => 200,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);
        $priceProductScheduleList = $this->tester->havePriceProductScheduleList([
            PriceProductScheduleListTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleList,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleList,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $applierMock = $this->getMockBuilder(ConcreteProductPriceProductScheduleApplier::class)
            ->disableOriginalConstructor()
            ->setMethods(['applyScheduledPrices'])
            ->getMock();

        $applierMock->expects($this->once())->method('applyScheduledPrices');
        $priceProductScheduleFacade = $this->getFacadeMockForConcretePrices($applierMock);

        // Act
        $priceProductScheduleFacade
            ->removePriceProductScheduleList($priceProductScheduleList->getIdPriceProductScheduleList());

        $priceProductScheduleListCollection = $this->tester
            ->getPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($priceProductScheduleList->getIdPriceProductScheduleList())
            ->find()
            ->getData();

        // Assert
        $this->assertCount(
            0,
            $priceProductScheduleListCollection,
            'Count of scheduled price lists does not match expected value'
        );
    }

    /**
     * @return void
     */
    public function testRemovePriceProductScheduleListInactiveListWithActiveConcretePricesShouldExecuteApply(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();
        $storeTransfer = $this->tester->haveStore();

        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => 'test1']);

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::PRICE_TYPE => $defaultPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $fallbackPriceTypeTransfer = $this->tester->havePriceType();

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::PRICE_TYPE => $fallbackPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 200,
                MoneyValueTransfer::GROSS_AMOUNT => 200,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);
        $priceProductScheduleList = $this->tester->havePriceProductScheduleList([
            PriceProductScheduleListTransfer::IS_ACTIVE => false,
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleList,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleList,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $applierMock = $this->getMockBuilder(ConcreteProductPriceProductScheduleApplier::class)
            ->disableOriginalConstructor()
            ->setMethods(['applyScheduledPrices'])
            ->getMock();

        $applierMock->expects($this->once())->method('applyScheduledPrices');
        $priceProductScheduleFacade = $this->getFacadeMockForConcretePrices($applierMock);

        // Act
        $priceProductScheduleFacade
            ->removePriceProductScheduleList($priceProductScheduleList->getIdPriceProductScheduleList());

        $priceProductScheduleListCollection = $this->tester
            ->getPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($priceProductScheduleList->getIdPriceProductScheduleList())
            ->find()
            ->getData();

        // Assert
        $this->assertCount(
            0,
            $priceProductScheduleListCollection,
            'Count of scheduled price lists does not match expected value'
        );
    }

    /**
     * @return void
     */
    public function testRemovePriceProductScheduleListWithInActiveAbstractPricesShouldNotExecuteApply(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();
        $storeTransfer = $this->tester->haveStore();

        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => 'test1']);

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $defaultPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $fallbackPriceTypeTransfer = $this->tester->havePriceType();

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $fallbackPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 200,
                MoneyValueTransfer::GROSS_AMOUNT => 200,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);
        $priceProductScheduleList = $this->tester->havePriceProductScheduleList([
            PriceProductScheduleListTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleList,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleList,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $applierMock = $this->getMockBuilder(AbstractProductPriceProductScheduleApplier::class)
            ->disableOriginalConstructor()
            ->setMethods(['applyScheduledPrices'])
            ->getMock();

        $applierMock->expects($this->never())->method('applyScheduledPrices');
        $priceProductScheduleFacade = $this->getFacadeMockForAbstractPrices($applierMock);

        // Act
        $priceProductScheduleFacade
            ->removePriceProductScheduleList($priceProductScheduleList->getIdPriceProductScheduleList());

        $priceProductScheduleListCollection = $this->tester
            ->getPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($priceProductScheduleList->getIdPriceProductScheduleList())
            ->find()
            ->getData();

        // Assert
        $this->assertCount(
            0,
            $priceProductScheduleListCollection,
            'Count of scheduled price lists does not match expected value'
        );
    }

    /**
     * @return void
     */
    public function testRemovePriceProductScheduleListWithInActiveConcretePricesShouldNotExecuteApply(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();
        $storeTransfer = $this->tester->haveStore();

        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => 'test1']);

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::PRICE_TYPE => $defaultPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $fallbackPriceTypeTransfer = $this->tester->havePriceType();

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::PRICE_TYPE => $fallbackPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 200,
                MoneyValueTransfer::GROSS_AMOUNT => 200,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);
        $priceProductScheduleList = $this->tester->havePriceProductScheduleList([
            PriceProductScheduleListTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleList,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleList,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $applierMock = $this->getMockBuilder(ConcreteProductPriceProductScheduleApplier::class)
            ->disableOriginalConstructor()
            ->setMethods(['applyScheduledPrices'])
            ->getMock();

        $applierMock->expects($this->never())->method('applyScheduledPrices');
        $priceProductScheduleFacade = $this->getFacadeMockForConcretePrices($applierMock);

        // Act
        $priceProductScheduleFacade
            ->removePriceProductScheduleList($priceProductScheduleList->getIdPriceProductScheduleList());

        $priceProductScheduleListCollection = $this->tester
            ->getPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($priceProductScheduleList->getIdPriceProductScheduleList())
            ->find()
            ->getData();

        // Assert
        $this->assertCount(
            0,
            $priceProductScheduleListCollection,
            'Count of scheduled price lists does not match expected value'
        );
    }

    /**
     * @param string $priceTypeName
     * @param string $fallbackPriceTypeName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected function getConfigMock(string $priceTypeName, string $fallbackPriceTypeName)
    {
        $configMock = $this->getMockBuilder(PriceProductScheduleConfig::class)
            ->setMethods(['getFallbackPriceTypeList'])
            ->getMock();

        $configMock->method('getFallbackPriceTypeList')
            ->willReturn([$priceTypeName => $fallbackPriceTypeName]);

        return $configMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $applierMock
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface
     */
    protected function getFacadeMockForAbstractPrices(MockObject $applierMock)
    {
        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleBusinessFactory = $this->getFactoryMock($applierMock, 'createAbstractProductPriceProductScheduleApplier');
        $priceProductScheduleFacade->setFactory($priceProductScheduleBusinessFactory);

        return $priceProductScheduleFacade;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $applierMock
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface
     */
    protected function getFacadeMockForConcretePrices(MockObject $applierMock)
    {
        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleBusinessFactory = $this->getFactoryMock($applierMock, 'createConcreteProductPriceProductScheduleApplier');
        $priceProductScheduleFacade->setFactory($priceProductScheduleBusinessFactory);

        return $priceProductScheduleFacade;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $applierMock
     * @param string $mockFactoryMethod
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getFactoryMock(MockObject $applierMock, string $mockFactoryMethod): MockObject
    {
        $priceProductScheduleBusinessFactory = $this->getMockBuilder(PriceProductScheduleBusinessFactory::class)
            ->setMethods([
                $mockFactoryMethod,
                'getRepository',
                'getEntityManager',
                'getProvidedDependency',
                'getStoreFacade',
                'getConfig',
                'getPriceProductFacade',
            ])
            ->getMock();
        $storeFacade = new PriceProductScheduleToStoreFacadeBridge(
            $this->tester->getLocator()->store()->facade()
        );
        $priceProductFacade = new PriceProductScheduleToPriceProductFacadeBridge(
            $this->tester->getLocator()->priceProduct()->facade()
        );
        $priceProductScheduleConfig = new PriceProductScheduleConfig();
        $priceProductScheduleBusinessFactory->method('getStoreFacade')
            ->willReturn($storeFacade);
        $priceProductScheduleBusinessFactory->method('getConfig')
            ->willReturn($priceProductScheduleConfig);
        $priceProductScheduleBusinessFactory->method('getPriceProductFacade')
            ->willReturn($priceProductFacade);
        $priceProductScheduleBusinessFactory->method('getEntityManager')
            ->willReturn(new PriceProductScheduleEntityManager());
        $priceProductScheduleBusinessFactory->method('getRepository')
            ->willReturn(new PriceProductScheduleRepository());

        $priceProductScheduleBusinessFactory
            ->method($mockFactoryMethod)
            ->willReturn($applierMock);

        return $priceProductScheduleBusinessFactory;
    }
}
