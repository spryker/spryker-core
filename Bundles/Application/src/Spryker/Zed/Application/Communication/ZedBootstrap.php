<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication;

use Spryker\Zed\Application\Communication\Bootstrap\Extension\BeforeBootExtension;
use Spryker\Zed\Application\Communication\Bootstrap\Extension\GlobalTemplateVariablesExtension;
use Spryker\Shared\Application\Communication\Application;
use Spryker\Shared\Application\Communication\Bootstrap;
use Spryker\Zed\Application\Communication\Bootstrap\Extension\RouterExtension;
use Spryker\Zed\Application\Communication\Bootstrap\Extension\ServiceProviderExtension;
use Spryker\Zed\Application\Communication\Bootstrap\Extension\TwigExtension;
use Spryker\Zed\Application\Communication\Plugin\Pimple;

class ZedBootstrap extends Bootstrap
{

    public function __construct()
    {
        parent::__construct($this->getBaseApplication());

        $this->addBeforeBootExtension(
            $this->getBeforeBootExtension()
        );

        $this->addServiceProviderExtension(
            $this->getServiceProviderExtension()
        );

        $this->addRouterExtension(
            $this->getRouterExtension()
        );

        $this->addTwigExtension(
            $this->getTwigExtension()
        );

        $this->addGlobalTemplateVariableExtension(
            $this->getGlobalTemplateVariablesExtension()
        );
    }

    /**
     * @return \Spryker\Shared\Application\Communication\Application
     */
    protected function getBaseApplication()
    {
        $application = new Application();

        $this->unsetSilexExceptionHandler($application);

        Pimple::setApplication($application);

        return $application;
    }

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @return void
     */
    private function unsetSilexExceptionHandler(Application $application)
    {
        unset($application['exception_handler']);
    }

    /**
     * @return \Spryker\Zed\Application\Communication\Bootstrap\Extension\BeforeBootExtension
     */
    protected function getBeforeBootExtension()
    {
        return new BeforeBootExtension();
    }

    /**
     * @return \Spryker\Zed\Application\Communication\Bootstrap\Extension\ServiceProviderExtension
     */
    protected function getServiceProviderExtension()
    {
        return new ServiceProviderExtension();
    }

    /**
     * @return \Spryker\Zed\Application\Communication\Bootstrap\Extension\RouterExtension
     */
    protected function getRouterExtension()
    {
        return new RouterExtension();
    }

    /**
     * @return \Spryker\Zed\Application\Communication\Bootstrap\Extension\TwigExtension
     */
    protected function getTwigExtension()
    {
        return new TwigExtension();
    }

    /**
     * @return \Spryker\Zed\Application\Communication\Bootstrap\Extension\GlobalTemplateVariablesExtension
     */
    protected function getGlobalTemplateVariablesExtension()
    {
        return new GlobalTemplateVariablesExtension();
    }

}
