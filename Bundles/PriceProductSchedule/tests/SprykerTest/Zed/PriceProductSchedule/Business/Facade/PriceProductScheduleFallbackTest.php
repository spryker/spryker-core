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
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
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
 * @group PriceProductScheduleFallbackTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleFallbackTest extends Unit
{
    public const DEFAULT_PRICE_TYPE_ID = 1;
    public const PRICE_TYPE_ID = 2;
    public const PRICE_TYPE_NAME_ORIGINAL = PriceProductScheduleConfig::PRICE_TYPE_ORIGINAL;

    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->priceProductFacade = $this->tester->getLocator()->priceProduct()->facade();
        $this->storeFacade = $this->tester->getLocator()->store()->facade();
        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
    }

    /**
     * @return void
     */
    public function testProductPriceShouldBeRevertedAfterPriceProductScheduleIsOver(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceTypeTransfer1 = $this->tester->havePriceType();
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'test']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);

        $this->tester->havePriceProduct([
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::ID_PRICE_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer1,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $priceTypeTransfer2 = $this->tester->havePriceType();

        $productPrice2 = $this->tester->havePriceProduct([
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::ID_PRICE_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer2,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 200,
                MoneyValueTransfer::GROSS_AMOUNT => 200,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $priceTypeTransfer1->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $priceTypeTransfer1->getIdPriceType(),
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

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->setFactory(
            (new PriceProductScheduleBusinessFactory())
                ->setConfig($this->getConfigMock($priceTypeTransfer1->getName(), $priceTypeTransfer2->getName()))
        );

        // Act
        $priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceTypeName($priceTypeTransfer1->getName())
            ->setCurrencyIsoCode($currencyTransfer->getCode());

        $priceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        $this->assertEquals(
            $productPrice2->getMoneyValue()->getNetAmount(),
            $priceProductTransfer->getMoneyValue()->getNetAmount(),
            'Product price type should be reverted after scheduled price is over.'
        );
        $this->assertEquals(
            $productPrice2->getMoneyValue()->getGrossAmount(),
            $priceProductTransfer->getMoneyValue()->getGrossAmount(),
            'Product price type should be reverted after scheduled price is over.'
        );
    }

    /**
     * @return void
     */
    public function testProductPriceShouldBeRemovedIfFallbackPriceTypeNotConfigured(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule($this->getPriceProductScheduleData($productConcreteTransfer));

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->setFactory((new PriceProductScheduleBusinessFactory())->setConfig($this->getNotConfiguredConfigMock()));

        // Act
        $priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setStoreName($this->storeFacade->getCurrentStore()->getName())
            ->setCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getCurrency())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());

        $priceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);
        $this->assertNull(
            $priceProductTransfer,
            'Product price type should be removed after scheduled price is over if no fallback price os configured.'
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return array
     */
    protected function getPriceProductScheduleData(ProductConcreteTransfer $productConcreteTransfer): array
    {
        $currencyId = $this->tester->haveCurrency();
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $priceType = $this->tester->havePriceType();

        return [
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-1 hour')),
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
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
        ];
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    protected function getPriceProductQuery(): SpyPriceProductQuery
    {
        return new SpyPriceProductQuery();
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected function getNotConfiguredConfigMock()
    {
        $configMock = $this->getMockBuilder(PriceProductScheduleConfig::class)
            ->setMethods(['getFallbackPriceTypeList'])
            ->getMock();

        $configMock->method('getFallbackPriceTypeList')
            ->willReturn([]);

        return $configMock;
    }
}
