<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group GetIdPriceProductTest
 * Add your own group annotations below this line
 */
class GetIdPriceProductTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Store\StoreDependencyProvider::STORE_CURRENT
     *
     * @var string
     */
    protected const STORE_CURRENT = 'STORE_CURRENT';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const SERVICE_CURRENCY = 'currency';

    /**
     * @var string
     */
    protected const SERVICE_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const DEFAULT_LOCALE = 'en';

    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(static::STORE_CURRENT, static::STORE_NAME);
        $container = $this->tester->getContainer();
        $container->set(static::SERVICE_CURRENCY, PriceProductBusinessTester::EUR_ISO_CODE);
        $container->set(static::SERVICE_LOCALE, static::DEFAULT_LOCALE);
    }

    /**
     * @return void
     */
    public function testGetIdPriceProductShouldReturnIdOfPriceProductEntity(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();
        $priceProductTransfer = $this->tester->createProductWithAmount(50, 40);

        // Act
        $idPriceProduct = $priceProductFacade->getIdPriceProduct(
            $priceProductTransfer->getSkuProduct(),
            $priceProductFacade->getDefaultPriceTypeName(),
            $this->tester->getCurrencyFacade()->getCurrent()->getCode(),
        );

        // Assert
        $this->assertSame($idPriceProduct, $priceProductTransfer->getIdPriceProduct());
    }
}
