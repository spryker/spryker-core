<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\DummyPayment;

use Spryker\Shared\Library\Currency\CurrencyManagerInterface;
use Spryker\Yves\DummyPayment\DummyPaymentDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @group Spryker
 * @group Yves
 * @group DummyPayment
 * @group DummyPaymentDependencyProvider
 */
class DummyPaymentDependencyProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testProvideDependenciesShouldAddCurrencyManager()
    {
        $container = new Container();
        $dummyPaymentDependencyProvider = new DummyPaymentDependencyProvider();
        $container = $dummyPaymentDependencyProvider->provideDependencies($container);

        $this->assertArrayHasKey(DummyPaymentDependencyProvider::CURRENCY_MANAGER, $container);
        $this->assertInstanceOf(CurrencyManagerInterface::class, $container[DummyPaymentDependencyProvider::CURRENCY_MANAGER]);
    }

}
