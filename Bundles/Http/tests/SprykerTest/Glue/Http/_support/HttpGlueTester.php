<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Glue\Http;

use Codeception\Actor;
use Codeception\Stub;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
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
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class HttpGlueTester extends Actor
{
    use _generated\HttpGlueTesterActions;

    /**
     * @return \Symfony\Component\HttpKernel\Event\ResponseEvent
     */
    public function getResponseEvent(): ResponseEvent
    {
        return new ResponseEvent($this->getHttpKernelMock(), Request::createFromGlobals(), HttpKernelInterface::MASTER_REQUEST, new Response());
    }

    /**
     * @return \Symfony\Component\HttpKernel\Event\RequestEvent
     */
    public function getRequestEvent(): RequestEvent
    {
        return new RequestEvent($this->getHttpKernelMock(), Request::createFromGlobals(), HttpKernelInterface::MASTER_REQUEST);
    }

    /**
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected function getHttpKernelMock(): HttpKernelInterface
    {
        /** @var \Symfony\Component\HttpKernel\HttpKernelInterface $httpKernelMock */
        $httpKernelMock = Stub::makeEmpty(HttpKernelInterface::class);

        return $httpKernelMock;
    }
}
