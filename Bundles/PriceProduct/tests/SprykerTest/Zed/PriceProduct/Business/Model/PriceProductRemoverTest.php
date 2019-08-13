<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
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
    protected $priceProductFactory;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProduct\PriceProductRemoverInterface
     */
    protected $priceProductRemover;

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Shared\PriceProduct\PriceProductConfig
     */
    protected $priceProductConfig;

    /**
     * @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    protected $priceProductQuery;

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
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceTypeTransfer = $this->tester->havePriceType();
        $currencyId = $this->tester->haveCurrency();
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);

        $priceProductTransfer = $this->tester->havePriceProduct([
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::ID_PRICE_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        // Act
        $this->priceProductRemover->removePriceProductStore($priceProductTransfer);

        // Assert
        $priceProductEntity = $this->priceProductQuery->findOneByIdPriceProduct($priceProductTransfer->getIdPriceProduct());

        $this->assertNull($priceProductEntity, 'Price product should be removed');
    }
}
