<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\StepEngine\Form;

use Spryker\Client\Cart\CartClientInterface;
use Spryker\Yves\StepEngine\CheckoutDependencyProvider;
use Spryker\Yves\StepEngine\Process\StepFactory;
use Spryker\Yves\StepEngine\Process\StepProcess;
use Spryker\Yves\StepEngine\Process\StepProcessInterface;
use Spryker\Yves\Kernel\Container;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @group Spryker
 * @group Yves
 * @group StepEngine
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

    /**
     * @return void
     */
    public function testCreateStepProcess()
    {
        $stepFactory = new StepFactory();
        $stepProcess = $stepFactory->createStepProcess(
            [],
            $this->getUrlGeneratorMock(),
            $this->getCartClientMock(),
            'errorRoute'
        );

        $this->assertInstanceOf(StepProcessInterface::class, $stepProcess);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private function getUrlGeneratorMock()
    {
        return $this->getMock(UrlGeneratorInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Cart\CartClientInterface
     */
    private function getCartClientMock()
    {
        return $this->getMock(CartClientInterface::class);
    }

}
