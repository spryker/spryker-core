<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Currency\Business\CurrencyFacade;
use Spryker\Zed\PriceProduct\Business\PriceProductFacade;
use Spryker\Zed\Store\Business\StoreFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group PriceProductFacadeTest
 * Add your own group annotations below this line
 */
class PriceProductFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetPriceTypeValuesShouldReturnListOfAllPersistedPriceTypes()
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypesBefore = $priceProductFacade->getPriceTypeValues();

        $priceProductFacade->createPriceType('test');

        $priceTypesAfter = $priceProductFacade->getPriceTypeValues();

        $this->assertCount(count($priceTypesBefore) + 1, $priceTypesAfter);
    }

    /**
     * @return void
     */
    public function testGetPriceBySkuShouldReturnDefaultPriceForGivenProduct()
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(50, 40);

        $price = $priceProductFacade->getPriceBySku($priceProductTransfer->getSkuProduct());

        $this->assertSame(50, $price);
    }

    /**
     * @return void
     */
    public function testGetPriceForShouldReturnPriceBasedOnFilter()
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer1 = $this->createProductWithAmount(50, 40);
        $priceProductTransfer2 = $this->createProductWithAmount(
            100,
            90,
            $priceProductTransfer1->getSkuProductAbstract(),
            $priceProductTransfer1->getSkuProduct(),
            'USD'
        );

        $priceProductFilterTransfer = new PriceProductFilterTransfer();
        $priceProductFilterTransfer->setCurrencyIsoCode('USD');
        $priceProductFilterTransfer->setSku($priceProductTransfer2->getSkuProduct());

        $price = $priceProductFacade->getPriceFor($priceProductFilterTransfer);

        $this->assertSame(100, $price);
    }

    /**
     * @return void
     */
    public function testCreatePriceType()
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $idPriceType = $priceProductFacade->createPriceType('test price type');

        $this->assertNotEmpty($idPriceType);
    }

    /**
     * @return void
     */
    public function testSetPriceForProductShouldUpdateExistingPrice()
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(50, 40);

        $priceProductTransfer->getMoneyValue()->setGrossAmount(100);

        $priceProductFacade->setPriceForProduct($priceProductTransfer);

        $price = $priceProductFacade->getPriceBySku($priceProductTransfer->getSkuProduct());

        $this->assertSame(100 , $price);
    }

    /**
     * @return void
     */
    public function testHasValidPriceShouldReturnTrueWhenProductHavePrices()
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(50, 40);

        $this->assertTrue(
            $priceProductFacade->hasValidPrice($priceProductTransfer->getSkuProduct())
        );
    }

    /**
     * @return void
     */
    public function testHasValidPriceForReturnTrueWhenProductHavePrices()
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(50, 40);

        $priceProductFilterTransfer = new PriceProductFilterTransfer();
        $priceProductFilterTransfer->setSku($priceProductTransfer->getSkuProduct());

        $this->assertTrue(
            $priceProductFacade->hasValidPriceFor($priceProductFilterTransfer)
        );
    }

    /**
     * @return void
     */
    public function testGetDefaultPriceTypeNameShouldReturnDefaultTypeName()
    {
        $priceProductFacade = $this->getPriceProductFacade();
        $this->assertNotEmpty($priceProductFacade->getDefaultPriceTypeName());
    }

    /**
     * @return void
     */
    public function testGetIdPriceProductShouldReturnIdOfPriceProductEntity()
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(50, 40);

        $idProductProduct = $priceProductFacade->getIdPriceProduct(
            $priceProductTransfer->getSkuProduct(),
            $priceProductFacade->getDefaultPriceTypeName(),
            $this->createCurrencyFacade()->getCurrent()->getCode()
        );

        $this->assertSame($idProductProduct, $priceProductTransfer->getIdPriceProduct());
    }

    /**
     * @return void
     */
    public function testPersistProductAbstractPriceCollectionShouldSavePriceCollection()
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10 ,9 ,'EUR');
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 11 ,10 ,'USD');

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setSku($productConcreteTransfer->getAbstractSku())
            ->setPrices($prices);

        $productAbstractTransfer = $priceProductFacade->persistProductAbstractPriceCollection($productAbstractTransfer);

        foreach ($productAbstractTransfer->getPrices() as $priceProductTransfer) {
            $this->assertNotEmpty($priceProductTransfer->getIdPriceProduct());
            $this->assertNotEmpty($priceProductTransfer->getMoneyValue()->getIdEntity());
        }
    }

    /**
     * @return void
     */
    public function testPersistProductConcretePriceCollectionShouldSavePriceCollection()
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10 ,9 ,'EUR');

        $productConcreteTransfer->setPrices($prices);

        $productConcreteTransfer = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer);

        foreach ($productConcreteTransfer->getPrices() as $priceProductTransfer) {
            $this->assertNotEmpty($priceProductTransfer->getIdPriceProduct());
            $this->assertNotEmpty($priceProductTransfer->getMoneyValue()->getIdEntity());
        }
    }

    /**
     * @param int $grossAmount
     * @param int $netAmount
     * @param string $skuAbstract
     * @param string $skuConcrete
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createProductWithAmount(
        $grossAmount,
        $netAmount,
        $skuAbstract = '',
        $skuConcrete = '',
        $currencyIsoCode = ''
    )
    {
        $priceProductTransfer = new PriceProductTransfer();
        $priceProductTransfer->setSkuProductAbstract($skuAbstract);
        $priceProductTransfer->setSkuProduct($skuConcrete);

        if (!$skuAbstract || !$skuConcrete) {
            $productConcreteTransfer = $this->tester->haveProduct();
            $priceProductTransfer->setSkuProductAbstract($productConcreteTransfer->getAbstractSku());
            $priceProductTransfer->setSkuProduct($productConcreteTransfer->getSku());
        }

        $storeTransfer = $this->createStoreFacade()->getCurrentStore();
        if (!$currencyIsoCode) {
            $currencyTransfer = $this->createCurrencyFacade()->getDefaultCurrencyForCurrentStore();
        }  else {
            $currencyTransfer = $this->createCurrencyFacade()->fromIsoCode($currencyIsoCode);
        }

        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setNetAmount($netAmount)
            ->setGrossAmount($grossAmount)
            ->setFkStore($storeTransfer->getIdStore())
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setCurrency($currencyTransfer);

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $this->getPriceProductFacade()->createPriceForProduct($priceProductTransfer);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function getPriceProductFacade()
    {
        return new PriceProductFacade();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function createStoreFacade()
    {
        return new StoreFacade();
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function createCurrencyFacade()
    {
        return new CurrencyFacade();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     * @param int $netPrice
     * @param int $grossPrice
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        PriceTypeTransfer $priceTypeTransfer,
        $netPrice,
        $grossPrice,
        $currencyIsoCode
    ) {
        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setSkuProductAbstract($productConcreteTransfer->getAbstractSku())
            ->setSkuProduct($productConcreteTransfer->getSku())
            ->setPriceTypeName($this->getPriceProductFacade()->getDefaultPriceTypeName())
            ->setPriceType($priceTypeTransfer);

        $currencyTransfer = $this->createCurrencyFacade()->fromIsoCode($currencyIsoCode);
        $storeTransfer = $this->createStoreFacade()->getCurrentStore();

        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setGrossAmount($grossPrice)
            ->setNetAmount($netPrice)
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setCurrency($currencyTransfer)
            ->setFkStore($storeTransfer->getIdStore());

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $priceProductTransfer;
    }
}
