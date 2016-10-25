<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DummyPayment;

use Spryker\Zed\DummyPayment\Dependency\Facade\DummyPaymentToRefundInterface;
use Spryker\Zed\DummyPayment\DummyPaymentDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group DummyPayment
 * @group DummyPaymentDependencyProviderTest
 */
class DummyPaymentDependencyProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependenciesShouldAddRefundFacade()
    {
        $container = new Container();
        $dummyPaymentDependencyProvider = new DummyPaymentDependencyProvider();
        $container = $dummyPaymentDependencyProvider->provideBusinessLayerDependencies($container);

        $this->assertArrayHasKey(DummyPaymentDependencyProvider::FACADE_REFUND, $container);
        $this->assertInstanceOf(DummyPaymentToRefundInterface::class, $container[DummyPaymentDependencyProvider::FACADE_REFUND]);
    }

}
