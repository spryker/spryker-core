<?php

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
        return $this->getDependencyContainer()->getCheckSteps();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function runCheckStepCodeCeption(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->getCheckStepCodeCeption($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function runCheckStepDeleteDatabase(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->getCheckStepDeleteDatabase($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function runCheckStepDeleteGeneratedDirectory(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->getCheckStepDeleteGeneratedDirectory($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function runCheckStepExportKeyValue(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->getCheckStepExportKeyValue($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function runCheckStepExportSearch(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->getCheckStepExportSearch($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function runCheckStepInstallDemoData(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->getCheckStepInstallDemoData($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function runCheckStepSetupInstall(LoggerInterface $logger = null)
    {
        $this->getDependencyContainer()->getCheckStepSetupInstall($logger)->run();
    }

    /**
     * @param $pathInfo
     *
     * @return array
     */
    public function buildNavigation($pathInfo)
    {
        return $this->getDependencyContainer()->getNavigationBuilder()->build($pathInfo);
    }
}
