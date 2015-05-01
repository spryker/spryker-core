<?php

namespace SprykerEngine\Yves\Application\Business;

use Generated\Yves\Ide\AutoCompletion;
use SprykerFeature\Shared\Application\Business\Application as SharedApplication;
use SprykerEngine\Yves\Application\Business\Application as YvesApplication;
use SprykerFeature\Shared\Application\Business\Bootstrap;
use SprykerEngine\Yves\Kernel\Locator;

abstract class YvesBootstrap extends Bootstrap
{

    /**
     * @return \SprykerEngine\Yves\Application\Communication\Plugin\ControllerProviderInterface[]
     */
    abstract protected function getControllerProviders();

    /**
     * @return SharedApplication|YvesApplication
     */
    protected function getBaseApplication()
    {
        return new YvesApplication();
    }

    /**
     * @param SharedApplication $app
     */
    protected function addProvidersToApp(SharedApplication $app)
    {
        parent::addProvidersToApp($app);

        foreach ($this->getControllerProviders() as $provider) {
            $app->mount($provider->getUrlPrefix(), $provider);
        }
    }

    /**
     * @param SharedApplication $app
     *
     * @return \Twig_Extension[]
     */
    protected function getTwigExtensions(SharedApplication $app)
    {
        /** @var AutoCompletion $locator */
        $locator = Locator::getInstance();
        $yvesExtension = $locator->twig()->pluginTwigYves($app);

        return [
            $yvesExtension->getTwigYvesExtension($app)
        ];
    }

    /**
     * @param SharedApplication $app
     *
     * @return array
     */
    protected function globalTemplateVariables(SharedApplication $app)
    {
        return parent::globalTemplateVariables($app);
    }

}
