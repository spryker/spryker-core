<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Payolution\Dependency\Injector;

use Codeception\Test\Unit;
use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Payolution\Dependency\Injector\CheckoutDependencyInjector;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Zed\Payolution\PayolutionConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Payolution
 * @group Dependency
 * @group Injector
 * @group CheckoutDependencyInjectorTest
 * Add your own group annotations below this line
 */
class CheckoutDependencyInjectorTest extends Unit
{
    /**
     * @return void
     */
    public function testInjectInjectsPaymentSubFormAndHandler()
    {
        $container = $this->getContainerToInjectTo();

        $checkoutDependencyInjector = new CheckoutDependencyInjector();
        $checkoutDependencyInjector->inject($container);

        $checkoutSubFormPluginCollection = $container[CheckoutDependencyProvider::PAYMENT_SUB_FORMS];
        $this->assertCount(2, $checkoutSubFormPluginCollection);

        $checkoutStepHandlerPluginCollection = $container[CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER];

        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PayolutionConfig::PAYMENT_METHOD_INVOICE));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PayolutionConfig::PAYMENT_METHOD_INSTALLMENT));
    }

    /**
     * @return \Spryker\Yves\Kernel\Container
     */
    private function getContainerToInjectTo()
    {
        $container = new Container();
        $container[CheckoutDependencyProvider::PAYMENT_SUB_FORMS] = function () {
            return new SubFormPluginCollection();
        };
        $container[CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER] = function () {
            return new StepHandlerPluginCollection();
        };

        return $container;
    }
}
