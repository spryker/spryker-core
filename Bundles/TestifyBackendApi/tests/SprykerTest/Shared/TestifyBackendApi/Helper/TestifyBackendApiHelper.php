<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\TestifyBackendApi\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class TestifyBackendApiHelper extends Module
{
    use ContainerHelperTrait;

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    protected const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        $this->getContainerHelper()->getContainer()->set(static::SERVICE_REQUEST_STACK, $this->createRequestStack());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    protected function createRequestStack(): RequestStack
    {
        $session = $this->createSession();
        $request = $this->createRequest($session);

        $stack = new RequestStack();
        $stack->push($request);

        return $stack;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected function createSession(): SessionInterface
    {
        return new Session(new MockArraySessionStorage());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createRequest(SessionInterface $session): Request
    {
        $request = Request::createFromGlobals();
        $request->setSession($session);

        return $request;
    }
}
