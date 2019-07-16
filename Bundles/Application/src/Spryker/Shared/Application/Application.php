<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

class Application implements HttpKernelInterface, TerminableInterface
{
    /**
     * @uses \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @uses \Symfony\Component\HttpFoundation\Request
     */
    public const SERVICE_REQUEST = 'request';

    /**
     * @uses \Symfony\Component\HttpFoundation\RequestStack
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @var \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected $plugins = [];

    /**
     * @var \Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface[]
     */
    protected $bootablePlugins = [];

    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface $applicationPlugin
     *
     * @return $this
     */
    public function registerApplicationPlugin(ApplicationPluginInterface $applicationPlugin)
    {
        $this->plugins[] = $applicationPlugin;
        $this->container = $applicationPlugin->provide($this->container);

        if ($applicationPlugin instanceof BootableApplicationPluginInterface) {
            $this->bootablePlugins[] = $applicationPlugin;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function boot()
    {
        if (!$this->booted) {
            $this->booted = true;
            $this->bootPlugins();
        }

        return $this;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $request = Request::createFromGlobals();

        $response = $this->handle($request);
        $response->send();
        $this->terminate($request, $response);
    }

    /**
     * @internal This method is called from the run() method and is for internal use only.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $type
     * @param bool $catch
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true): Response
    {
        $this->container->set('request', $request);
        $this->flushControllers();

        $response = $this->getKernel()->handle($request);

        return $response;
    }

    /**
     * @deprecated Will be removed without replacement. This method was only used for Silex Controller.
     *
     * @return void
     */
    public function flushControllers()
    {
        $this->container->get('routes')
            ->addCollection($this->container->get('controllers')->flush());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        $this->getKernel()->terminate($request, $response);
    }

    /**
     * @return void
     */
    protected function bootPlugins(): void
    {
        foreach ($this->bootablePlugins as $bootablePlugin) {
            $this->container = $bootablePlugin->boot($this->container);
        }
    }

    /**
     * @return \Symfony\Component\HttpKernel\HttpKernel
     */
    protected function getKernel(): HttpKernel
    {
        return $this->container->get('kernel');
    }
}
