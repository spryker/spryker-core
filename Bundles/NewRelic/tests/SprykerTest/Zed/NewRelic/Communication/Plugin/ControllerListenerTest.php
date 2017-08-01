<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\NewRelic\Communication\Plugin;

use PHPUnit_Framework_TestCase;
use Spryker\Service\UtilNetwork\UtilNetworkService;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\NewRelicApi\NewRelicApi;
use Spryker\Zed\NewRelic\Communication\Plugin\ControllerListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group NewRelic
 * @group Communication
 * @group Plugin
 * @group ControllerListenerTest
 * Add your own group annotations below this line
 */
class ControllerListenerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testIfTransactionIsInIgnoredListMarkIgnoreTransactionShouldBeCalled()
    {
        $newRelicApiMock = $this->getNewRelicApiMock();
        $newRelicApiMock->expects($this->once())->method('markIgnoreTransaction');

        $controllerListener = new ControllerListener($newRelicApiMock, Store::getInstance(), new UtilNetworkService(), ['bar/baz']);
        $request = new Request();
        $request->attributes->set('module', 'foo');
        $request->attributes->set('controller', 'bar');
        $request->attributes->set('action', 'baz');

        $controller = function () {};
        $filterControllerEvent = new FilterControllerEvent(
            $this->getKernelMock(),
            $controller,
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $controllerListener->onKernelController($filterControllerEvent);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\NewRelicApi\NewRelicApiInterface
     */
    protected function getNewRelicApiMock()
    {
        $newRelicApiMock = $this->getMockBuilder(NewRelicApi::class)
            ->setMethods([
                'markIgnoreTransaction',
                'setNameOfTransaction',
                'addCustomParameter',
            ])
            ->getMock();

        return $newRelicApiMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected function getKernelMock()
    {
        $kernelMock = $this->getMockBuilder(HttpKernelInterface::class)
            ->getMock();

        return $kernelMock;
    }

}
