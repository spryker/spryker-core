<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Spryker\Shared\PriceProduct\PriceProductConfig;
use Spryker\Zed\PriceProduct\Business\PriceProductBusinessFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Model
 * @group PriceProductRemoverTest
 * Add your own group annotations below this line
 */
class PriceProductRemoverTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductBusinessFactory
     */
    private $priceProductFactory;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProduct\PriceProductRemoverInterface
     */
    private $priceProductRemover;

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    private $priceProductFacade;

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    private $storeFacade;

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    private $currencyFacade;

    /**
     * @var \Spryker\Shared\PriceProduct\PriceProductConfig
     */
    private $priceProductConfig;

    /**
     * @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    private $priceProductQuery;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->priceProductFacade = $this->tester->getFacade();
        $this->priceProductFactory = new PriceProductBusinessFactory();
        $this->priceProductRemover = $this->priceProductFactory->createPriceProductRemover();
        $this->storeFacade = $this->tester->getLocator()->store()->facade();
        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
        $this->priceProductConfig = new PriceProductConfig();
        $this->priceProductQuery = new SpyPriceProductQuery();
    }

    /**
     * @return void
     */
    public function testRemovePriceProductStore(): void
    {
        // Assign
        $priceProductTransfer = $this->createProductWithAmount(100, 90);

        // Act
        $this->priceProductRemover->removePriceProductStore($priceProductTransfer);

        // Assert
        $priceProductEntity = $this->priceProductQuery->findOneByIdPriceProduct($priceProductTransfer->getIdPriceProduct());

        $this->assertNull($priceProductEntity);
    }

    /**
     * @param int $grossAmount
     * @param int $netAmount
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createProductWithAmount($grossAmount, $netAmount): PriceProductTransfer
    {
        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($this->priceProductConfig->getPriceDimensionDefault());

        $basePriceProductTransfer = (new PriceProductTransfer())
            ->setPriceDimension($priceProductDimensionTransfer);

        $priceProductTransfer = $this->buildProduct($basePriceProductTransfer);

        $storeTransfer = $this->storeFacade->getCurrentStore();

        $currencyTransfer = $this->currencyFacade->getDefaultCurrencyForCurrentStore();

        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setNetAmount($netAmount)
            ->setGrossAmount($grossAmount)
            ->setFkStore($storeTransfer->getIdStore())
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setCurrency($currencyTransfer);

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $this->priceProductFacade->createPriceForProduct($priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function buildProduct(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductTransfer
            ->setSkuProductAbstract($productConcreteTransfer->getAbstractSku())
            ->setSkuProduct($productConcreteTransfer->getSku())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());

        return $priceProductTransfer;
    }
}
