<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Router;

use Codeception\Actor;
use Spryker\Service\Container\Container;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Router\Resolver\ControllerResolver;
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
class RouterTester extends Actor
{
    use _generated\RouterTesterActions;

    /**
     * @var array
     */
    protected $calledControllerMethods = [];

    /**
     * @param string $methodName
     *
     * @return void
     */
    public function addCalledControllerMethod(string $methodName)
    {
        $this->calledControllerMethods[$methodName] = true;
    }

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
    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequestWithUnresolvableController(): Request
    {
        $request = $this->getRequest();

        $request->attributes->set('_controller', 123);

        return $request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequestWithCallableController(): Request
    {
        $request = $this->getRequest();
        $request->attributes->set('_controller', [
            function () {
                return 'ControllerCallable';
            },
            'actionName',
        ]);

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
    public function getRequestWithInvalidControllerString(): Request
    {
        $request = $this->getRequest();
        $request->attributes->set('_controller', 'invalid-string');

        return $request;
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
    public function getRequestWithInstantiableClass(): Request
    {
        $request = $this->getRequest();
        $request->attributes->set('_controller', ['stdClass', 'actionName']);

        return $request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequestWithInvokableControllerObject(): Request
    {
        $request = $this->getRequest();

        $controllerMock = $this->getInvokableControllerMock($this);
        $request->attributes->set('_controller', $controllerMock);

        return $request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequestWithNotInvokableControllerObject(): Request
    {
        $request = $this->getRequest();
        $controllerMock = new class {
        };
        $request->attributes->set('_controller', $controllerMock);

        return $request;
    }

    /**
     * @param \SprykerTest\Shared\Router\RouterTester $tester
     *
     * @return callable
     */
    public function getInvokableControllerMock(RouterTester $tester)
    {
        $this->calledControllerMethods = [];

        return new class ($tester) {

            /**
             * @var \SprykerTest\Shared\Router\RouterTester
             */
            protected $tester;

            /**
             * @var \Spryker\Service\Container\ContainerInterface
             */
            protected $container;

            /**
             * @param \SprykerTest\Shared\Router\RouterTester $tester
             */
            public function __construct(RouterTester $tester)
            {
                $this->tester = $tester;
            }

            /**
             * @param \Spryker\Service\Container\ContainerInterface $container
             *
             * @return void
             */
            public function setApplication(ContainerInterface $container): void
            {
                $this->container = $container;

                $this->tester->addCalledControllerMethod('setApplication');
            }

            /**
             * @return void
             */
            public function initialize(): void
            {
                $this->tester->addCalledControllerMethod('initialize');
            }

            /**
             * @return string
             */
            public function __invoke(): string
            {
                $this->tester->addCalledControllerMethod('__invoke');

                return 'Controller';
            }
        };
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

    /**
     * @return void
     */
    public function assertSetApplicationAndInitializeCalledOnController(): void
    {
        $this->assertTrue(isset($this->calledControllerMethods['setApplication']));
        $this->assertTrue(isset($this->calledControllerMethods['initialize']));
    }

    /**
     * @return void
     */
    public function assertInvokeCalledOnController(): void
    {
        $this->assertTrue(isset($this->calledControllerMethods['setApplication']));
        $this->assertTrue(isset($this->calledControllerMethods['initialize']));
    }
}
