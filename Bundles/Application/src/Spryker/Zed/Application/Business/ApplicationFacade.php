<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Psr\Log\LoggerInterface;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationBusinessFactory getFactory()
 */
class ApplicationFacade extends AbstractFacade implements ApplicationFacadeInterface
{

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return \Spryker\Zed\Application\Communication\Console\ApplicationCheckStep\AbstractApplicationCheckStep[]
     */
    public function getCheckSteps(LoggerInterface $logger = null)
    {
        return $this->getFactory()->createCheckSteps($logger);
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepCodeCeption(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepCodeCeption($logger)->run();
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepDeleteDatabase(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepDeleteDatabase($logger)->run();
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepDeleteGeneratedDirectory(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepDeleteGeneratedDirectory($logger)->run();
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepExportKeyValue(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepExportKeyValue($logger)->run();
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepExportSearch(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepExportSearch($logger)->run();
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepInstallDemoData(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepInstallDemoData($logger)->run();
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepSetupInstall(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepSetupInstall($logger)->run();
    }

    /**
     * @param string $pathInfo
     *
     * @return array
     */
    public function buildNavigation($pathInfo)
    {
        return $this->getFactory()->createNavigationBuilder()->build($pathInfo);
    }

    /**
     * @return void
     */
    public function writeNavigationCache()
    {
        $this->getFactory()->createNavigationCacheBuilder()->writeNavigationCache();
    }

}
