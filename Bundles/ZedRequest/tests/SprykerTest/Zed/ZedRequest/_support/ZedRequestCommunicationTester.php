<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedRequest;

use Codeception\Actor;
use Codeception\Stub;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ZedRequestCommunicationTester extends Actor
{
    use _generated\ZedRequestCommunicationTesterActions;

    /**
     * @param callable $controller
     *
     * @return \Symfony\Component\HttpKernel\Event\FilterControllerEvent
     */
    public function createFilterControllerEvent(callable $controller): FilterControllerEvent
    {
        $filterControllerEvent = new FilterControllerEvent(
            $this->getHttpKernelMock(),
            $controller,
            Request::createFromGlobals(),
            HttpKernelInterface::MASTER_REQUEST
        );

        return $filterControllerEvent;
    }

    /**
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected function getHttpKernelMock()
    {
        /** @var \Symfony\Component\HttpKernel\HttpKernelInterface $httpKernelMock */
        $httpKernelMock = Stub::makeEmpty(HttpKernelInterface::class);

        return $httpKernelMock;
    }
}
