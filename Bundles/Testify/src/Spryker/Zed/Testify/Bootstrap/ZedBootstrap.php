<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Bootstrap;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\ControllerResolver\ZedFragmentControllerResolver;
use Symfony\Component\HttpFoundation\Request;

class ZedBootstrap
{

    /**
     * @var \Silex\ServiceProviderInterface[]
     */
    private $serviceProvider;

    /**
     * @var \Silex\Application
     */
    private $application;

    /**
     * @param \Silex\ServiceProviderInterface[] $serviceProvider
     */
    public function __construct(array $serviceProvider)
    {
        $this->serviceProvider = $serviceProvider;
    }

    /**
     * @return mixed
     */
    public function boot()
    {
        $application = $this->getApplication();
        $application['debug'] = true;
        $application['locale'] = $this->getCurrentLocale();

        $this->addFragmentControllerResolver();
        $this->enableHttpMethodParameterOverride();

        $this->registerServiceProvider();
        $this->addVariablesToTwig();

        return $application;
    }

    /**
     * @return \Silex\Application|\Spryker\Shared\Kernel\Communication\Application
     */
    private function getApplication()
    {
        if (!$this->application) {

            $application = new Application();
            $this->unsetSilexExceptionHandler($application);
            Pimple::setApplication($application);

            $this->application = $application;
        }

        return $this->application;
    }

    /**
     * @return void
     */
    protected function addFragmentControllerResolver()
    {
        $application = $this->getApplication();
        $application['resolver'] = $application->share(function () use ($application) {
            return new ZedFragmentControllerResolver($application);
        });
    }

    private function registerServiceProvider()
    {
        foreach ($this->serviceProvider as $serviceProviderClassName) {
            $serviceProvider = new $serviceProviderClassName;
            $this->getApplication()->register($serviceProvider);
        }
    }

    /**
     * @return void
     */
    protected function addVariablesToTwig()
    {
        $application = $this->application;
        $application['twig.global.variables'] = $application->share(
            $application->extend('twig.global.variables', function (array $variables) use ($application) {
                $variables += [
                    'environment' => APPLICATION_ENV,
                    'store' => Store::getInstance()->getStoreName(),
                    'title' => 'Testify | Zed | ' . ucfirst(APPLICATION_ENV),
                    'currentController' => get_class($this),
                ];

                return $variables;
            })
        );
    }

    /**
     * Allow overriding http method. Needed to use the "_method" parameter in forms.
     * This should not be changeable by projects
     *
     * @return void
     */
    private function enableHttpMethodParameterOverride()
    {
        Request::enableHttpMethodParameterOverride();
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $application
     *
     * @return void
     */
    private function unsetSilexExceptionHandler(Application $application)
    {
        unset($application['exception_handler']);
    }

    /**
     * @return string
     */
    private function getCurrentLocale()
    {
        $store = Store::getInstance();

        return $store->getCurrentLocale();
    }

}
