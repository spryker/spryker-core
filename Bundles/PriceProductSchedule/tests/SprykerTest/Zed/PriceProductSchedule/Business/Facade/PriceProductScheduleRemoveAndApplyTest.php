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
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSchedule
 * @group Business
 * @group Facade
 * @group PriceProductScheduleRemoveAndApplyTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleRemoveAndApplyTest extends Unit
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
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $storeTransfer;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty();

        $this->priceProductScheduleFacade = $this->tester->getFacade();
        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
        $this->storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->priceProductFacade = $this->tester->getLocator()->priceProduct()->facade();
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleAbstractDeleteAndApplyShouldSetDefaultPriceFromOriginal(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'test1']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);

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

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $this->storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->setFactory(
            (new PriceProductScheduleBusinessFactory())
                ->setConfig($this->getConfigMock($defaultPriceTypeTransfer->getName(), $fallbackPriceTypeTransfer->getName())),
        );

        // Act
        $priceProductScheduleFacade->applyScheduledPrices($this->storeTransfer->getName());
        $priceProductScheduleFacade->removeAndApplyPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceTypeName($defaultPriceTypeTransfer->getName())
            ->setCurrencyIsoCode($currencyTransfer->getCode())
            ->setStoreName($this->storeTransfer->getName());

        $actualPriceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        $this->assertSame(200, $actualPriceProductTransfer->getMoneyValue()->getNetAmount(), 'Default price does not match expected value.');
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleConcreteDeleteAndApplyShouldSetDefaultPriceFromOriginal(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'test2']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $defaultPriceTypeTransfer,
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
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
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 200,
                MoneyValueTransfer::GROSS_AMOUNT => 200,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $this->storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->setFactory(
            (new PriceProductScheduleBusinessFactory())
                ->setConfig($this->getConfigMock($defaultPriceTypeTransfer->getName(), $fallbackPriceTypeTransfer->getName())),
        );

        // Act
        $priceProductScheduleFacade->applyScheduledPrices($this->storeTransfer->getName());
        $priceProductScheduleFacade->removeAndApplyPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceTypeName($defaultPriceTypeTransfer->getName())
            ->setCurrencyIsoCode($currencyTransfer->getCode())
            ->setStoreName($this->storeTransfer->getName());

        $actualPriceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        $this->assertSame(200, $actualPriceProductTransfer->getMoneyValue()->getNetAmount(), 'Default price does not match expected value.');
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleAbstractDeleteAndApplyShouldSetDefaultPriceFromSecondScheduledPrice(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'test3']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);

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

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $this->storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-6 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+6 days')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $this->storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 500,
                    MoneyValueTransfer::GROSS_AMOUNT => 500,
                ],
            ],
        ]);

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->setFactory(
            (new PriceProductScheduleBusinessFactory())
                ->setConfig($this->getConfigMock($defaultPriceTypeTransfer->getName(), $fallbackPriceTypeTransfer->getName())),
        );

        // Act
        $priceProductScheduleFacade->applyScheduledPrices($this->storeTransfer->getName());
        $priceProductScheduleFacade->removeAndApplyPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getAbstractSku())
            ->setPriceTypeName($defaultPriceTypeTransfer->getName())
            ->setCurrencyIsoCode($currencyTransfer->getCode())
            ->setStoreName($this->storeTransfer->getName());

        $actualPriceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        $this->assertSame(500, $actualPriceProductTransfer->getMoneyValue()->getNetAmount(), 'Default price does not match expected value.');
    }

    /**
     * @return void
     */
    public function testPriceProductScheduleConcreteDeleteAndApplyShouldSetDefaultPriceFromAnotherScheduledPrice(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'test4']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $defaultPriceTypeTransfer,
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
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
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 200,
                MoneyValueTransfer::GROSS_AMOUNT => 200,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $this->storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-6 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+6 days')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $this->storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 500,
                    MoneyValueTransfer::GROSS_AMOUNT => 500,
                ],
            ],
        ]);

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->setFactory(
            (new PriceProductScheduleBusinessFactory())
                ->setConfig($this->getConfigMock($defaultPriceTypeTransfer->getName(), $fallbackPriceTypeTransfer->getName())),
        );

        // Act
        $priceProductScheduleFacade->applyScheduledPrices($this->storeTransfer->getName());
        $priceProductScheduleFacade->removeAndApplyPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceTypeName($defaultPriceTypeTransfer->getName())
            ->setCurrencyIsoCode($currencyTransfer->getCode())
            ->setStoreName($this->storeTransfer->getName());

        $actualPriceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        $this->assertSame(500, $actualPriceProductTransfer->getMoneyValue()->getNetAmount(), 'Default price does not match expected value.');
    }

    /**
     * @param string $priceTypeName
     * @param string $fallbackPriceTypeName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected function getConfigMock(string $priceTypeName, string $fallbackPriceTypeName): PriceProductScheduleConfig
    {
        $configMock = $this->getMockBuilder(PriceProductScheduleConfig::class)
            ->onlyMethods(['getFallbackPriceTypeList'])
            ->getMock();

        $configMock->method('getFallbackPriceTypeList')
            ->willReturn([$priceTypeName => $fallbackPriceTypeName]);

        return $configMock;
    }
}
