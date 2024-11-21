<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Customer;

use Codeception\Actor;
use Codeception\Stub;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
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
class CustomerTester extends Actor
{
    use _generated\CustomerTesterActions;

    /**
     * @param array $sessionSeed
     *
     * @return \Symfony\Component\HttpKernel\Event\RequestEvent
     */
    public function getRequestEvent(array $sessionSeed = []): RequestEvent
    {
        $request = Request::createFromGlobals();

        $request->setSession($this->getHttpSessionMock($sessionSeed));

        return new RequestEvent($this->getHttpKernelMock(), $request, HttpKernelInterface::MAIN_REQUEST);
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

    /**
     * @param array $returnedValues
     *
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected function getHttpSessionMock(array $returnedValues = []): SessionInterface
    {
        return Stub::makeEmpty(SessionInterface::class, [
            'get' => function ($key) use (&$returnedValues) {
                return $returnedValues[$key] ?? null;
            },
            'set' => function ($key, $value) use (&$returnedValues) {
                $returnedValues[$key] = $value;
            },
        ]);
    }
}
