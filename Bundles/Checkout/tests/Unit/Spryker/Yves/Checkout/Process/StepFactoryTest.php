<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Checkout\Process;

use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Checkout\Process\StepFactory;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface;
use Spryker\Yves\StepEngine\Process\StepCollection;
use Spryker\Yves\StepEngine\Process\StepEngineInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Checkout
 * @group Process
 * @group StepFactoryTest
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Cart\CartClientInterface
     */
    private function getDataContainerMock()
    {
        return $this->getMockBuilder(DataContainerInterface::class)->getMock();
    }

}
