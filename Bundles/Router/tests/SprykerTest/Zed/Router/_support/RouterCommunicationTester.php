<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Router;

use Codeception\Actor;
use Spryker\Service\Container\Container;
use Spryker\Zed\Router\Communication\Resolver\ControllerResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

/**
 * Inherited Methods
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
class RouterCommunicationTester extends Actor
{
    use _generated\RouterCommunicationTesterActions;

    /**
     * @param array $services
     *
     * @return \Symfony\Component\HttpKernel\Controller\ControllerResolverInterface
     */
    public function getControllerResolver(array $services = []): ControllerResolverInterface
    {
        return new ControllerResolver(new Container($services));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequestWithControllerUrl(): Request
    {
        $request = $this->getRequest();
        $request->attributes->set('_controller', '/router/mock/mock');

        return $request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequestWithControllerService(): Request
    {
        $request = $this->getRequest();
        $request->attributes->set('_controller', 'ControllerServiceName:actionName');

        return $request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    /**
     * @param string $controller
     * @param array $resolvedController
     *
     * @return void
     */
    public function assertController(string $controller, array $resolvedController): void
    {
        if (is_object($resolvedController[0])) {
            $resolvedController[0] = get_class($resolvedController[0]);
        }

        $this->assertSame($controller, $resolvedController[0]);
    }
}
