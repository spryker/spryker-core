<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DummyPayment\Dependency\Injector;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\DummyPayment\DummyPaymentConstants;
use Spryker\Zed\DummyPayment\Dependency\Injector\PaymentDependencyInjector;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Payment\PaymentDependencyProvider;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group DummyPayment
 * @group Dependency
 * @group Injector
 * @group PaymentDependencyInjectorTest
 */
class PaymentDependencyInjectorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testInjectBusinessLayerDependenciesShouldAddPlugins()
    {
        $container = new Container();
        $paymentDependencyProvider = new PaymentDependencyProvider();
        $paymentDependencyProvider->provideBusinessLayerDependencies($container);

        $paymentDependencyInjector = new PaymentDependencyInjector();
        $paymentDependencyInjector->injectBusinessLayerDependencies($container);

        $pluginCollection = $this->getPluginCollectionFromContainer($container);

        $this->assertTrue($pluginCollection->has(DummyPaymentConstants::PROVIDER_NAME, PaymentDependencyProvider::CHECKOUT_PRE_CHECK_PLUGINS));
        $this->assertTrue($pluginCollection->has(DummyPaymentConstants::PROVIDER_NAME, PaymentDependencyProvider::CHECKOUT_ORDER_SAVER_PLUGINS));
        $this->assertTrue($pluginCollection->has(DummyPaymentConstants::PROVIDER_NAME, PaymentDependencyProvider::CHECKOUT_POST_SAVE_PLUGINS));
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollection
     */
    protected function getPluginCollectionFromContainer($container)
    {
        $pluginCollection = $container[PaymentDependencyProvider::CHECKOUT_PLUGINS];

        return $pluginCollection;
    }

}
