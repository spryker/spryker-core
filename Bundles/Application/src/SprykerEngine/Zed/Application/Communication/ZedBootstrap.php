<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Application\Communication;

use SprykerEngine\Zed\Application\Communication\Bootstrap\Extension\AfterBootExtension;
use SprykerEngine\Zed\Application\Communication\Bootstrap\Extension\BeforeBootExtension;
use SprykerEngine\Zed\Application\Communication\Bootstrap\Extension\GlobalTemplateVariablesExtension;
use SprykerEngine\Shared\Application\Communication\Application;
use SprykerEngine\Shared\Application\Communication\Bootstrap;
use SprykerEngine\Zed\Application\Communication\Bootstrap\Extension\RouterExtension;
use SprykerEngine\Zed\Application\Communication\Bootstrap\Extension\ServiceProviderExtension;
use SprykerFeature\Zed\Application\Communication\Plugin\Pimple;

class ZedBootstrap extends Bootstrap
{

    public function __construct()
    {
        parent::__construct($this->getBaseApplication());

        $this->addBeforeBootExtension(
            $this->getBeforeBootExtension()
        );

        $this->addAfterBootExtension(
            $this->getAfterBootExtension()
        );

        $this->addServiceProviderExtension(
            $this->getServiceProviderExtension()
        );

        $this->addRouterExtension(
            $this->getRouterExtension()
        );

        $this->addGlobalTemplateVariableExtension(
            $this->getGlobalTemplateVariablesExtension()
        );
    }

    /**
     * @return Application
     */
    protected function getBaseApplication()
    {
        $application = new Application();

        Pimple::setApplication($application);

        return $application;
    }

    /**
     * @return BeforeBootExtension
     */
    protected function getBeforeBootExtension()
    {
        return new BeforeBootExtension();
    }

    /**
     * @return AfterBootExtension
     */
    protected function getAfterBootExtension()
    {
        return new AfterBootExtension();
    }

    /**
     * @return ServiceProviderExtension
     */
    protected function getServiceProviderExtension()
    {
        return new ServiceProviderExtension();
    }

    /**
     * @return RouterExtension
     */
    protected function getRouterExtension()
    {
        return new RouterExtension();
    }

    /**
     * @return GlobalTemplateVariablesExtension
     */
    protected function getGlobalTemplateVariablesExtension()
    {
        return new GlobalTemplateVariablesExtension();
    }

}
