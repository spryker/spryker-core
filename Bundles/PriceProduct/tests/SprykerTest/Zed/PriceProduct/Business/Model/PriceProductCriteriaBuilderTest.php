<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PriceProduct\Business\Currency\CurrencyReaderInterface;
use Spryker\Zed\PriceProduct\Business\Currency\CurrencyReaderWithCache;
use Spryker\Zed\PriceProduct\Business\Model\PriceProductCriteriaBuilder;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeBridge;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeBridge;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Model
 * @group PriceProductCriteriaBuilderTest
 * Add your own group annotations below this line
 */
class PriceProductCriteriaBuilderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testBuildCriteriaFromFilterGetsCurrencyByStoreNameIfItIsProvided(): void
    {
        if ($this->tester->isDynamicStoreEnabled() === false) {
            $this->markTestSkipped('This test requires DynamicStore to be enabled.');
        }

        //Arrange
        $storeTransfer = $this->tester->haveStore();

        $expectedCurrencyTransfer = $this->tester->getLocator()->currency()->facade()
            ->getStoreWithCurrenciesByIdStore($storeTransfer->getIdStore());
        $priceProductFilterTransfer = $this->tester->havePriceProductFilterTransfer()
            ->setStoreName($storeTransfer->getName());

        //Act
        $priceProductCriteriaBuilder = new PriceProductCriteriaBuilder(
            $this->createCurrencyReader(),
            $this->createPriceFacadeMock(),
            $this->createStoreFacade(),
            $this->createPriceProductTypeReaderMock(),
            $this->createPriceProductConfigMock(),
        );
        $priceProductCriteriaTransfer = $priceProductCriteriaBuilder->buildCriteriaFromFilter($priceProductFilterTransfer);

        //Assert

        $currencyId = $expectedCurrencyTransfer->getCurrencies()[$storeTransfer->getDefaultCurrencyIsoCode()]
            ->getIdCurrency();
        $this->assertSame($priceProductCriteriaTransfer->getIdCurrency(), $currencyId);
    }

    /**
     * @return void
     */
    public function testBuildCriteriaFromFilterGetsDefaultCurrencyIfStoreNameIsNotProvided(): void
    {
        //Arrange
        /*
         * CurrencyFacade::getDefaultCurrencyForCurrentStore() requires a store to be set.
         */
        $this->tester->addCurrentStore($this->tester->haveStore([StoreTransfer::NAME => 'DE']));
        $priceProductFilterTransfer = $this->tester->havePriceProductFilterTransfer();
        $expectedCurrencyTransfer = $this->tester->getLocator()->currency()->facade()
            ->getDefaultCurrencyForCurrentStore();

        //Act
        $priceProductCriteriaBuilder = new PriceProductCriteriaBuilder(
            $this->createCurrencyReader(),
            $this->createPriceFacadeMock(),
            $this->createStoreFacade(),
            $this->createPriceProductTypeReaderMock(),
            $this->createPriceProductConfigMock(),
        );
        $priceProductCriteriaTransfer = $priceProductCriteriaBuilder->buildCriteriaFromFilter($priceProductFilterTransfer);

        //Assert
        $this->assertSame($priceProductCriteriaTransfer->getIdCurrency(), $expectedCurrencyTransfer->getIdCurrency());
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Currency\CurrencyReaderInterface
     */
    protected function createCurrencyReader(): CurrencyReaderInterface
    {
        return new CurrencyReaderWithCache(
            new PriceProductToCurrencyFacadeBridge(
                $this->tester->getLocator()->currency()->facade(),
            ),
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface
     */
    protected function createPriceFacadeMock(): PriceProductToPriceFacadeInterface
    {
        return $this->createMock(PriceProductToPriceFacadeInterface::class);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface
     */
    protected function createStoreFacade(): PriceProductToStoreFacadeInterface
    {
        return new PriceProductToStoreFacadeBridge(
            $this->tester->getLocator()->store()->facade(),
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface
     */
    protected function createPriceProductTypeReaderMock(): PriceProductTypeReaderInterface
    {
        return $this->createMock(PriceProductTypeReaderInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected function createPriceProductConfigMock(): PriceProductConfig
    {
        return $this->createMock(PriceProductConfig::class);
    }
}
