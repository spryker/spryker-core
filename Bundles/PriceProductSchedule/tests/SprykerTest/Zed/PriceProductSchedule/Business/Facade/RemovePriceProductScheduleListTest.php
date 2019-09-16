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
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Applier\AbstractProductPriceProductScheduleApplier;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Applier\ConcreteProductPriceProductScheduleApplier;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacade;

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
        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
        $this->storeFacade = $this->tester->getLocator()->store()->facade();
    }

    /**
     * @return void
     */
    public function testRemovePriceProductScheduleListWithActiveAbstractPricesShouldExecuteApply(): void
    {
        // Assign
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => '111']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);
        $productAbstract = $this->tester->haveProductAbstract();
        $priceProductScheduleListTransfer = $this->tester->havePriceProductScheduleList([
            PriceProductScheduleListTransfer::IS_ACTIVE => true,
        ]);
        $defaultPriceTypeTransfer = $this->tester->havePriceType();
        $idPriceProductScheduleList = $priceProductScheduleListTransfer->getIdPriceProductScheduleList();
        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::ACTIVE_FROM => new DateTime('-4 days'),
            PriceProductScheduleTransfer::ACTIVE_TO => new DateTime('+1 hour'),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstract->getIdProductAbstract(),
                PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productAbstract->getSku(),
            ],
        ]);
        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::ACTIVE_FROM => new DateTime('-4 days'),
            PriceProductScheduleTransfer::ACTIVE_TO => new DateTime('+1 hour'),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
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
        $priceProductScheduleFacade = new PriceProductScheduleFacade();
        $priceProductScheduleBusinessFactory = $this->getMockBuilder(PriceProductScheduleBusinessFactory::class)
            ->setMethods(['createAbstractProductPriceProductScheduleApplier'])
            ->getMock();

        $priceProductScheduleBusinessFactory
            ->method('createAbstractProductPriceProductScheduleApplier')
            ->willReturn($applierMock);

        $priceProductScheduleFacade->setFactory($priceProductScheduleBusinessFactory);

        // Act
        $priceProductScheduleFacade->removePriceProductScheduleList($idPriceProductScheduleList);

        $priceProductScheduleListCollection = $this->tester
            ->getPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($idPriceProductScheduleList)
            ->find();

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
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => '111']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);
        $productConcrete = $this->tester->haveProduct();
        $priceProductScheduleListTransfer = $this->tester->havePriceProductScheduleList([
            PriceProductScheduleListTransfer::IS_ACTIVE => true,
        ]);
        $defaultPriceTypeTransfer = $this->tester->havePriceType();
        $idPriceProductScheduleList = $priceProductScheduleListTransfer->getIdPriceProductScheduleList();
        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::ACTIVE_FROM => new DateTime('-4 days'),
            PriceProductScheduleTransfer::ACTIVE_TO => new DateTime('+1 hour'),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
                PriceProductTransfer::ID_PRODUCT => $productConcrete->getIdProductConcrete(),
                PriceProductTransfer::SKU_PRODUCT => $productConcrete->getSku(),
            ],
        ]);
        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::ACTIVE_FROM => new DateTime('-4 days'),
            PriceProductScheduleTransfer::ACTIVE_TO => new DateTime('+1 hour'),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
                PriceProductTransfer::ID_PRODUCT => $productConcrete->getIdProductConcrete(),
                PriceProductTransfer::SKU_PRODUCT => $productConcrete->getSku(),
            ],
        ]);

        $applierMock = $this->getMockBuilder(ConcreteProductPriceProductScheduleApplier::class)
            ->disableOriginalConstructor()
            ->setMethods(['applyScheduledPrices'])
            ->getMock();

        $applierMock->expects($this->once())->method('applyScheduledPrices');
        $priceProductScheduleFacade = new PriceProductScheduleFacade();
        $priceProductScheduleBusinessFactory = $this->getMockBuilder(PriceProductScheduleBusinessFactory::class)
            ->setMethods(['createConcreteProductPriceProductScheduleApplier'])
            ->getMock();

        $priceProductScheduleBusinessFactory
            ->method('createConcreteProductPriceProductScheduleApplier')
            ->willReturn($applierMock);

        $priceProductScheduleFacade->setFactory($priceProductScheduleBusinessFactory);

        // Act
        $priceProductScheduleFacade->removePriceProductScheduleList($idPriceProductScheduleList);

        $priceProductScheduleListCollection = $this->tester
            ->getPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($idPriceProductScheduleList)
            ->find();

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
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => '111']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);
        $productAbstract = $this->tester->haveProductAbstract();
        $priceProductScheduleListTransfer = $this->tester->havePriceProductScheduleList([
            PriceProductScheduleListTransfer::IS_ACTIVE => false,
        ]);
        $defaultPriceTypeTransfer = $this->tester->havePriceType();
        $idPriceProductScheduleList = $priceProductScheduleListTransfer->getIdPriceProductScheduleList();
        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::ACTIVE_FROM => new DateTime('-4 days'),
            PriceProductScheduleTransfer::ACTIVE_TO => new DateTime('+1 hour'),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstract->getIdProductAbstract(),
                PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productAbstract->getSku(),
            ],
        ]);
        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::ACTIVE_FROM => new DateTime('-4 days'),
            PriceProductScheduleTransfer::ACTIVE_TO => new DateTime('+1 hour'),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
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
        $priceProductScheduleFacade = new PriceProductScheduleFacade();
        $priceProductScheduleBusinessFactory = $this->getMockBuilder(PriceProductScheduleBusinessFactory::class)
            ->setMethods(['createAbstractProductPriceProductScheduleApplier'])
            ->getMock();

        $priceProductScheduleBusinessFactory
            ->method('createAbstractProductPriceProductScheduleApplier')
            ->willReturn($applierMock);

        $priceProductScheduleFacade->setFactory($priceProductScheduleBusinessFactory);

        // Act
        $priceProductScheduleFacade->removePriceProductScheduleList($idPriceProductScheduleList);

        $priceProductScheduleListCollection = $this->tester
            ->getPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($idPriceProductScheduleList)
            ->find();

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
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => '111']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);
        $productAbstract = $this->tester->haveProductAbstract();
        $priceProductScheduleListTransfer = $this->tester->havePriceProductScheduleList([
            PriceProductScheduleListTransfer::IS_ACTIVE => true,
        ]);
        $defaultPriceTypeTransfer = $this->tester->havePriceType();
        $idPriceProductScheduleList = $priceProductScheduleListTransfer->getIdPriceProductScheduleList();
        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::ACTIVE_FROM => new DateTime('-4 days'),
            PriceProductScheduleTransfer::ACTIVE_TO => new DateTime('+1 hour'),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstract->getIdProductAbstract(),
                PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productAbstract->getSku(),
            ],
        ]);
        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::ACTIVE_FROM => new DateTime('-4 days'),
            PriceProductScheduleTransfer::ACTIVE_TO => new DateTime('+1 hour'),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
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
        $priceProductScheduleFacade = new PriceProductScheduleFacade();
        $priceProductScheduleBusinessFactory = $this->getMockBuilder(PriceProductScheduleBusinessFactory::class)
            ->setMethods(['createAbstractProductPriceProductScheduleApplier'])
            ->getMock();

        $priceProductScheduleBusinessFactory
            ->method('createAbstractProductPriceProductScheduleApplier')
            ->willReturn($applierMock);

        $priceProductScheduleFacade->setFactory($priceProductScheduleBusinessFactory);

        // Act
        $priceProductScheduleFacade->removePriceProductScheduleList($idPriceProductScheduleList);

        $priceProductScheduleListCollection = $this->tester
            ->getPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($idPriceProductScheduleList)
            ->find();

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
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => '111']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);
        $productConcrete = $this->tester->haveProduct();
        $priceProductScheduleListTransfer = $this->tester->havePriceProductScheduleList([
            PriceProductScheduleListTransfer::IS_ACTIVE => true,
        ]);
        $defaultPriceTypeTransfer = $this->tester->havePriceType();
        $idPriceProductScheduleList = $priceProductScheduleListTransfer->getIdPriceProductScheduleList();
        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::ACTIVE_FROM => new DateTime('-4 days'),
            PriceProductScheduleTransfer::ACTIVE_TO => new DateTime('+1 hour'),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
                PriceProductTransfer::ID_PRODUCT => $productConcrete->getIdProductConcrete(),
                PriceProductTransfer::SKU_PRODUCT => $productConcrete->getSku(),
            ],
        ]);
        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::ACTIVE_FROM => new DateTime('-4 days'),
            PriceProductScheduleTransfer::ACTIVE_TO => new DateTime('+1 hour'),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
                PriceProductTransfer::ID_PRODUCT => $productConcrete->getIdProductConcrete(),
                PriceProductTransfer::SKU_PRODUCT => $productConcrete->getSku(),
            ],
        ]);

        $applierMock = $this->getMockBuilder(ConcreteProductPriceProductScheduleApplier::class)
            ->disableOriginalConstructor()
            ->setMethods(['applyScheduledPrices'])
            ->getMock();

        $applierMock->expects($this->once())->method('applyScheduledPrices');
        $priceProductScheduleFacade = new PriceProductScheduleFacade();
        $priceProductScheduleBusinessFactory = $this->getMockBuilder(PriceProductScheduleBusinessFactory::class)
            ->setMethods(['createConcreteProductPriceProductScheduleApplier'])
            ->getMock();

        $priceProductScheduleBusinessFactory
            ->method('createConcreteProductPriceProductScheduleApplier')
            ->willReturn($applierMock);

        $priceProductScheduleFacade->setFactory($priceProductScheduleBusinessFactory);

        // Act
        $priceProductScheduleFacade->removePriceProductScheduleList($idPriceProductScheduleList);

        $priceProductScheduleListCollection = $this->tester
            ->getPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($idPriceProductScheduleList)
            ->find();

        // Assert
        $this->assertCount(
            0,
            $priceProductScheduleListCollection,
            'Count of scheduled price lists does not match expected value'
        );
    }
}
