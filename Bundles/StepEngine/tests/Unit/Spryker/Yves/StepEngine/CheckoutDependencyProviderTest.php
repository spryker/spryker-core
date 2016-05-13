<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\StepEngine;

use Spryker\Yves\StepEngine\CheckoutDependencyProvider;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\CheckoutStepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\CheckoutSubFormPluginCollection;
use Spryker\Yves\Kernel\Container;

/**
 * @group Spryker
 * @group Yves
 * @group StepEngine
 * @group StepEngineDependencyProvider
 */
class CheckoutDependencyProviderTest extends \PHPUnit_Framework_TestCase
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
        $this->assertInstanceOf(CheckoutStepHandlerPluginCollection::class, $container[CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER]);

        $this->assertArrayHasKey(CheckoutDependencyProvider::PAYMENT_SUB_FORMS, $container);
        $this->assertInstanceOf(CheckoutSubFormPluginCollection::class, $container[CheckoutDependencyProvider::PAYMENT_SUB_FORMS]);
    }

}
