<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Subscriber;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Zed\Twig\Communication\RouteResolver\RouteResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    protected const SERVICE_REQUEST_STACK = 'request_stack';
    protected const SERVICE_TWIG = 'twig';

    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Spryker\Zed\Twig\Communication\RouteResolver\RouteResolverInterface
     */
    protected $routeResolver;

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param \Spryker\Zed\Twig\Communication\RouteResolver\RouteResolverInterface $routeResolver
     */
    public function __construct(ContainerInterface $container, RouteResolverInterface $routeResolver)
    {
        $this->container = $container;
        $this->routeResolver = $routeResolver;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => 'onKernelView',
        ];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     *
     * @return void
     */
    public function onKernelView(GetResponseForControllerResultEvent $event): void
    {
        $response = $event->getControllerResult();

        if ($response === null || is_array($response)) {
            $response = $this->getResponse((array)$response);
            if ($response !== null) {
                $event->setResponse($response);
            }
        }
    }

    /**
     * Renders the template for the current controller/action
     *
     * @param array $params
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    protected function getResponse(array $params = []): ?Response
    {
        $request = $this->getRequestStack()->getCurrentRequest();
        $controller = $request->attributes->get('_controller');

        if (!is_string($controller)) {
            return null;
        }

        return $this->render($this->buildViewName($controller, $params), $params);
    }

    /**
     * @param string $controller
     * @param array $params
     *
     * @return string
     */
    protected function buildViewName(string $controller, array $params = []): string
    {
        $route = $this->getRoute($controller, $params);

        return sprintf('@%s.twig', $route);
    }

    /**
     * @param string $viewName
     * @param array $params
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function render(string $viewName, array $params = []): Response
    {
        $twig = $this->getTwig();

        $response = new Response();
        $response->setContent($twig->render($viewName, $params));

        return $response;
    }

    /**
     * @param string $controller
     * @param array $params
     *
     * @return string
     */
    protected function getRoute(string $controller, array $params = []): string
    {
        if (isset($params['alternativeRoute'])) {
            return (string)$params['alternativeRoute'];
        }

        return $this->routeResolver
            ->buildRouteFromControllerServiceName($controller);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    protected function getRequestStack(): RequestStack
    {
        return $this->container->get(static::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwig(): Environment
    {
        return $this->container->get(static::SERVICE_TWIG);
    }
}
