<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Checkout\Model\Process;

use Codeception\Test\Unit;
use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Checkout\Process\StepFactory;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\StepEngine\Process\StepCollection;
use Spryker\Yves\StepEngine\Process\StepEngineInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Checkout
 * @group Model
 * @group Process
 * @group StepFactoryTest
 * Add your own group annotations below this line
 */
class StepFactoryTest extends Unit
{
    public const METHOD_HANDLER = 'method handler';

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
    public function testCreateStepEngine()
    {
        $stepFactory = new StepFactory();
        $stepProcess = $stepFactory->createStepEngine(
            new StepCollection($this->getUrlGeneratorMock(), 'escape-route')
        );

        $this->assertInstanceOf(StepEngineInterface::class, $stepProcess);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private function getUrlGeneratorMock()
    {
        return $this->getMockBuilder(UrlGeneratorInterface::class)->getMock();
    }
}
