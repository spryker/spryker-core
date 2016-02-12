<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business;

use Psr\Log\LoggerInterface;

interface ApplicationFacadeInterface
{

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return \Spryker\Zed\Application\Communication\Console\ApplicationCheckStep\AbstractApplicationCheckStep[]
     */
    public function getCheckSteps(LoggerInterface $logger = null);

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepCodeCeption(LoggerInterface $logger = null);

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepDeleteDatabase(LoggerInterface $logger = null);

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepDeleteGeneratedDirectory(LoggerInterface $logger = null);

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepExportKeyValue(LoggerInterface $logger = null);

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepExportSearch(LoggerInterface $logger = null);

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepInstallDemoData(LoggerInterface $logger = null);

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function runCheckStepSetupInstall(LoggerInterface $logger = null);

    /**
     * @param string $pathInfo
     *
     * @return array
     */
    public function buildNavigation($pathInfo);

    /**
     * @return void
     */
    public function writeNavigationCache();

}
