<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\CheckoutStepEngine\Form;

use Spryker\Yves\CheckoutStepEngine\CheckoutDependencyProvider;
use Spryker\Yves\CheckoutStepEngine\Process\StepFactory;
use Spryker\Yves\Kernel\Container;

/**
 * @group Spryker
 * @group Yves
 * @group CheckoutStepEngine
 * @group StepFactory
 */
class StepFactoryTest extends \PHPUnit_Framework_TestCase
{

    const METHOD_HANDLER = 'method handler';

    /**
     * @return void
     */
    public function testCreatePaymentMethodSubForms()
    {
        $container = new Container();
        $container[CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER] = self::METHOD_HANDLER;

        $stepFactory = new StepFactory();
        $stepFactory->setContainer($container);

        $this->assertSame(self::METHOD_HANDLER, $stepFactory->createPaymentMethodHandler());
    }

}
