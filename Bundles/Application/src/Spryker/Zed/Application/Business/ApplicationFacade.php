<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business;

use Spryker\Zed\Application\Communication\Console\ApplicationCheckStep\AbstractApplicationCheckStep;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Psr\Log\LoggerInterface;

/**
 * @method ApplicationBusinessFactory getBusinessFactory()
 */
class ApplicationFacade extends AbstractFacade
{

    /**
     * @param LoggerInterface $logger
     *
     * @return AbstractApplicationCheckStep[]
     */
    public function getCheckSteps(LoggerInterface $logger = null)
    {
        return $this->getBusinessFactory()->createCheckSteps($logger);
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepCodeCeption(LoggerInterface $logger = null)
    {
        $this->getBusinessFactory()->createCheckStepCodeCeption($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepDeleteDatabase(LoggerInterface $logger = null)
    {
        $this->getBusinessFactory()->createCheckStepDeleteDatabase($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepDeleteGeneratedDirectory(LoggerInterface $logger = null)
    {
        $this->getBusinessFactory()->createCheckStepDeleteGeneratedDirectory($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepExportKeyValue(LoggerInterface $logger = null)
    {
        $this->getBusinessFactory()->createCheckStepExportKeyValue($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepExportSearch(LoggerInterface $logger = null)
    {
        $this->getBusinessFactory()->createCheckStepExportSearch($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepInstallDemoData(LoggerInterface $logger = null)
    {
        $this->getBusinessFactory()->createCheckStepInstallDemoData($logger)->run();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepSetupInstall(LoggerInterface $logger = null)
    {
        $this->getBusinessFactory()->createCheckStepSetupInstall($logger)->run();
    }

    /**
     * @param string $pathInfo
     *
     * @return array
     */
    public function buildNavigation($pathInfo)
    {
        return $this->getBusinessFactory()->createNavigationBuilder()->build($pathInfo);
    }

    /**
     * @return void
     */
    public function writeNavigationCache()
    {
        $this->getBusinessFactory()->createNavigationCacheBuilder()->writeNavigationCache();
    }

}
