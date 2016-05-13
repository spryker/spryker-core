<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\StepEngine\Form;

use Spryker\Yves\StepEngine\CheckoutDependencyProvider;
use Spryker\Yves\StepEngine\Form\FormFactory;
use Spryker\Yves\Kernel\Container;

/**
 * @group Spryker
 * @group Yves
 * @group StepEngine
 * @group FormFactory
 */
class FormFactoryTest extends \PHPUnit_Framework_TestCase
{

    const SUB_FORMS = 'forms';

    /**
     * @return void
     */
    public function testCreatePaymentMethodSubForms()
    {
        $container = new Container();
        $container[CheckoutDependencyProvider::PAYMENT_SUB_FORMS] = self::SUB_FORMS;

        $formFactory = new FormFactory();
        $formFactory->setContainer($container);

        $this->assertSame(self::SUB_FORMS, $formFactory->createPaymentMethodSubForms());
    }

}
