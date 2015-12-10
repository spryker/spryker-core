<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Bootstrap;

use SprykerEngine\Shared\Application\Communication\Application as SharedApplication;
use SprykerEngine\Yves\Application\Application as YvesApplication;
use SprykerEngine\Shared\Application\Communication\Bootstrap;
use SprykerEngine\Yves\Application\Bootstrap\Extension\ControllerProviderExtensionInterface;

class YvesBootstrap extends Bootstrap
{

    /**
     * @var ControllerProviderExtensionInterface[]
     */
    private $controllerProviderExtensions = [];

    /**
     * @param YvesApplication $application
     */
    public function __construct(YvesApplication $application)
    {
        parent::__construct($application);
    }

    /**
     * @param ControllerProviderExtensionInterface $controllerProviderExtension
     *
     * @return self
     */
    public function addControllerProviderExtension(ControllerProviderExtensionInterface $controllerProviderExtension)
    {
        $this->controllerProviderExtensions[] = $controllerProviderExtension;

        return $this;
    }

    /**
     * @param SharedApplication $application
     *
     * @return void
     */
    protected function addProvidersToApp(SharedApplication $application)
    {
        parent::addProvidersToApp($application);

        foreach ($this->controllerProviderExtensions as $controllerProviderExtension) {
            $controllerProviderCollection = $controllerProviderExtension->getControllerProvider($application);
            foreach ($controllerProviderCollection as $controllerProvider) {
                $application->mount($controllerProvider->getUrlPrefix(), $controllerProvider);
            }
        }
    }

}
