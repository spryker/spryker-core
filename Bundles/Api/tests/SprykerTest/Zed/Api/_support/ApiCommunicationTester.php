<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api;

use Codeception\Actor;
use Codeception\Stub;
use Spryker\Zed\Api\Communication\Controller\RestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiCommunicationTester extends Actor
{
    use _generated\ApiCommunicationTesterActions;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpKernel\Event\ControllerEvent
     */
    public function getControllerEvent(Request $request): ControllerEvent
    {
        $filterControllerEvent = new ControllerEvent($this->getKernelMock(), $this->getController(), $request, Kernel::MASTER_REQUEST);

        return $filterControllerEvent;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getApiRequest(): Request
    {
        return new Request([], [], [], [], [], ['REQUEST_URI' => '/api/rest/controller/action']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getNonApiRequest(): Request
    {
        return new Request([], [], [], [], [], ['REQUEST_URI' => '/module/controller/action']);
    }

    /**
     * @return \Symfony\Component\HttpKernel\KernelInterface
     */
    protected function getKernelMock(): KernelInterface
    {
        /** @var \Symfony\Component\HttpKernel\KernelInterface $kernelMock */
        $kernelMock = Stub::makeEmpty(KernelInterface::class);

        return $kernelMock;
    }

    /**
     * @return callable
     */
    protected function getController()
    {
        return [
            new RestController(),
            'indexAction',
        ];
    }
}
