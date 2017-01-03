<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Checkout;

use PHPUnit_Framework_TestCase;
use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Checkout
 * @group CheckoutDependencyProviderTest
 */
class CheckoutDependencyProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testProvideDependencies()
    {
        $container = new Container();
        $checkoutDependencyProvider = new CheckoutDependencyProvider();
        $checkoutDependencyProvider->provideDependencies($container);

        $this->assertArrayHasKey(CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER, $container);
        $this->assertInstanceOf(StepHandlerPluginCollection::class, $container[CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER]);

        $this->assertArrayHasKey(CheckoutDependencyProvider::PAYMENT_SUB_FORMS, $container);
        $this->assertInstanceOf(SubFormPluginCollection::class, $container[CheckoutDependencyProvider::PAYMENT_SUB_FORMS]);
    }

}
