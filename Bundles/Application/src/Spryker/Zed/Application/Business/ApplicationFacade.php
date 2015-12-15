<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business;

use Spryker\Zed\Application\Communication\Console\ApplicationCheckStep\AbstractApplicationCheckStep;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Psr\Log\LoggerInterface;

/**
 * @method ApplicationDependencyContainer getDependencyContainer()
 */
class ApplicationFacade extends AbstractFacade
{

    /**
     * @return AbstractApplicationCheckStep[]
     */
    public function getCheckSteps()
    {
        return $this->getDependencyContainer()->createCheckSteps();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepCodeCeption(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepCodeCeption($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepDeleteDatabase(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepDeleteDatabase($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepDeleteGeneratedDirectory(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepDeleteGeneratedDirectory($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepExportKeyValue(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepExportKeyValue($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepExportSearch(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepExportSearch($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepInstallDemoData(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepInstallDemoData($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepSetupInstall(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepSetupInstall($logger)->run();
    }

    /**
     * @param string $pathInfo
     *
     * @return array
     */
    public function buildNavigation($pathInfo)
    {
        return $this->getDependencyContainer()->createNavigationBuilder()->build($pathInfo);
    }

    /**
     * @return void
     */
    public function writeNavigationCache()
    {
        $this->getDependencyContainer()->createNavigationCacheBuilder()->writeNavigationCache();
    }

}
