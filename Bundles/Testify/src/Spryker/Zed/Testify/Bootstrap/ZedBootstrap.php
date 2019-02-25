<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Bootstrap;

use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * TODO: Adjust this bootstrap class according to replacement ServiceProviders to Plugins and new application Container.
 */
class ZedBootstrap
{
    /**
     * @var \Silex\ServiceProviderInterface[]
     */
    private $additionalServiceProvider;

    /**
     * @var \Silex\Application
     */
    private $application;

    /**
     * @param \Silex\ServiceProviderInterface[] $additionalServiceProvider
     */
    public function __construct(array $additionalServiceProvider)
    {
        $this->additionalServiceProvider = $additionalServiceProvider;
    }

    /**
     * @return mixed
     */
    public function boot()
    {
        $application = $this->getApplication();
        $application['debug'] = true;
        $application['locale'] = $this->getCurrentLocale();

        $this->registerServiceProvider();
        $application['session.test'] = true;

        return $this->application;
    }

    /**
     * @return \Silex\Application|\Spryker\Shared\Kernel\Communication\Application
     */
    private function getApplication()
    {
        $application = new Application();
        $this->unsetSilexExceptionHandler($application);
        Pimple::setApplication($application);

        $this->application = $application;

        return $this->application;
    }

    /**
     * @return void
     */
    private function registerServiceProvider()
    {
        $serviceProviders = $this->getServiceProvider();
        foreach ($serviceProviders as $serviceProvider) {
            if (!($serviceProvider instanceof ServiceProviderInterface)) {
                $serviceProvider = new $serviceProvider();
            }
            $this->application->register($serviceProvider);
        }
    }

    /**
     * @return array
     */
    private function getServiceProvider()
    {
        $defaultServiceProviders = $this->getDefaultServiceProvider();

        return array_merge($defaultServiceProviders, $this->additionalServiceProvider);
    }

    /**
     * @return array
     */
    private function getDefaultServiceProvider()
    {
        return [
            new FormServiceProvider(),
            new HttpFragmentServiceProvider(),
            new ServiceControllerServiceProvider(),
            new ValidatorServiceProvider(),
            new SessionServiceProvider(),
            new TwigServiceProvider(),
        ];
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
