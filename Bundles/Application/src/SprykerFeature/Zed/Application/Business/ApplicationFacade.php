<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business;

use SprykerFeature\Zed\Application\Communication\Console\ApplicationCheckStep\AbstractApplicationCheckStep;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
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
     */
    public function runCheckStepCodeCeption(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepCodeCeption($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function runCheckStepDeleteDatabase(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepDeleteDatabase($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function runCheckStepDeleteGeneratedDirectory(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepDeleteGeneratedDirectory($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function runCheckStepExportKeyValue(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepExportKeyValue($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function runCheckStepExportSearch(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepExportSearch($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function runCheckStepInstallDemoData(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->createCheckStepInstallDemoData($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
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

    public function writeNavigationCache()
    {
        $this->getDependencyContainer()->createNavigationCacheBuilder()->writeNavigationCache();
    }

}
