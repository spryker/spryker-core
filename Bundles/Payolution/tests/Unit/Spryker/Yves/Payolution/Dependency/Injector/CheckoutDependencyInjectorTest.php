<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Payolution\Dependency\Injector;

use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Yves\CheckoutStepEngine\CheckoutDependencyProvider;
use Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\CheckoutStepHandlerPluginCollection;
use Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\CheckoutSubFormPluginCollection;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Payolution\Dependency\Injector\CheckoutDependencyInjector;

/**
 * @group Spryker
 * @group Yves
 * @group Payolution
 * @group CheckoutDependencyInjector
 */
class CheckoutDependencyInjectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testInject()
    {
        $container = $this->getContainerToInjectTo();

        $checkoutDependencyInjector = new CheckoutDependencyInjector();
        $checkoutDependencyInjector->inject($container);

        $checkoutSubFormPluginCollection = $container[CheckoutDependencyProvider::PAYMENT_SUB_FORMS];
        $this->assertCount(2, $checkoutSubFormPluginCollection);

        $checkoutStepHandlerPluginCollection = $container[CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER];
//        $this->assertCount(2, $checkoutStepHandlerPluginCollection);

        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYOLUTION_INVOICE));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYOLUTION_INSTALLMENT));
    }

    /**
     * @return \Spryker\Yves\Kernel\Container
     */
    private function getContainerToInjectTo()
    {
        $container = new Container();
        $container[CheckoutDependencyProvider::PAYMENT_SUB_FORMS] = function () {
            return new CheckoutSubFormPluginCollection();
        };
        $container[CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER] = function () {
            return new CheckoutStepHandlerPluginCollection();
        };

        return $container;
    }

}
