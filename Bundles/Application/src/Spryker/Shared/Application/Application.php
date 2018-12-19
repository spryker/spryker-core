<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Provider\BootableServiceInterface;
use Spryker\Shared\ApplicationExtension\Provider\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

class Application implements HttpKernelInterface, TerminableInterface
{
    /**
     * @var \Spryker\Shared\ApplicationExtension\Provider\ServiceProviderInterface[]
     */
    protected $services = [];

    /**
     * @var \Spryker\Shared\ApplicationExtension\Provider\BootableServiceInterface[]
     */
    protected $bootableServices = [];

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
     * @param \Spryker\Shared\ApplicationExtension\Provider\ServiceProviderInterface $serviceProvider
     *
     * @return $this
     */
    public function registerServiceProvider(ServiceProviderInterface $serviceProvider)
    {
        $this->services[] = $serviceProvider;
        $serviceProvider->provide($this->container);

        if ($serviceProvider instanceof BootableServiceInterface) {
            $this->bootableServices[] = $serviceProvider;
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
            $this->bootServices();
        }

        return $this;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $request = Request::createFromGlobals();

        $this->container->set('request', $request);

        $response = $this->handle($request);
        $response->send();
        $this->terminate($request, $response);
    }

    /**
     * @internal Don't use this method unless you know why.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $type
     * @param bool $catch
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true): Response
    {
        $response = $this->getKernel()->handle($request);

        return $response;
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
    protected function bootServices(): void
    {
        foreach ($this->bootableServices as $bootableService) {
            $bootableService->boot($this->container);
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
