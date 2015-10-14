<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication;

use SprykerEngine\Shared\Application\Communication\Application as SharedApplication;
use SprykerEngine\Yves\Application\Communication\Application as YvesApplication;
use SprykerEngine\Shared\Application\Communication\Bootstrap;
use SprykerEngine\Yves\Application\Communication\Bootstrap\Extension\ControllerProviderExtensionInterface;

class YvesBootstrap extends Bootstrap
{

    /**
     * @var ControllerProviderExtensionInterface[]
     */
    private $controllerProviderExtensions = [];

    public function __construct()
    {
        parent::__construct(new YvesApplication());
    }

    /**
     * @param ControllerProviderExtensionInterface $controllerProviderExtension
     *
     * @return $this
     */
    public function addControllerProviderExtension(ControllerProviderExtensionInterface $controllerProviderExtension)
    {
        $this->controllerProviderExtensions[] = $controllerProviderExtension;

        return $this;
    }

    /**
     * @param SharedApplication $app
     */
    protected function addProvidersToApp(SharedApplication $app)
    {
        parent::addProvidersToApp($app);

        foreach ($this->controllerProviderExtensions as $controllerProviderExtension) {
            $controllerProviderCollection = $controllerProviderExtension->getControllerProvider($app);
            foreach ($controllerProviderCollection as $controllerProvider) {
                $app->mount($controllerProvider->getUrlPrefix(), $controllerProvider);
            }
        }
    }

}
