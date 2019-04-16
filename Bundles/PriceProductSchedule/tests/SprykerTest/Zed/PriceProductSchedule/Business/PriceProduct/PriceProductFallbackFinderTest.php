<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\PriceProduct;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSchedule
 * @group Business
 * @group PriceProduct
 * @group PriceProductFallbackFinderTest
 * Add your own group annotations below this line
 */
class PriceProductFallbackFinderTest extends Unit
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
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
    }

    /**
     * @return void
     */
    public function testFindFallbackPriceProduct(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceTypeTransfer1 = $this->tester->havePriceType();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'BBB']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);

        $productPrice1 = $this->tester->havePriceProduct([
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

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleBusinessFactory = (new PriceProductScheduleBusinessFactory())
            ->setConfig($this->getConfigMock($priceTypeTransfer1->getName(), $priceTypeTransfer2->getName()));
        $priceProductScheduleFacade->setFactory($priceProductScheduleBusinessFactory);
        $priceProductFallbackFinder = $priceProductScheduleBusinessFactory->createPriceProductFallbackFinder();

        // Act
        $fallBackProductPrice = $priceProductFallbackFinder->findFallbackPriceProduct($productPrice1);

        // Assert
        $this->assertEquals($productPrice2->getIdPriceProduct(), $fallBackProductPrice->getIdPriceProduct());
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
}
