<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationBusinessFactory getFactory()
 */
class ApplicationFacade extends AbstractFacade implements ApplicationFacadeInterface
{

    /**
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return \Spryker\Zed\Application\Communication\Console\ApplicationCheckStep\AbstractApplicationCheckStep[]
     */
    public function getCheckSteps(LoggerInterface $logger = null)
    {
        return $this->getFactory()->createCheckSteps($logger);
    }

    /**
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function runCheckStepCodeCeption(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepCodeCeption($logger)->run();
    }

    /**
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function runCheckStepDeleteDatabase(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepDeleteDatabase($logger)->run();
    }

    /**
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function runCheckStepDeleteGeneratedDirectory(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepDeleteGeneratedDirectory($logger)->run();
    }

    /**
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function runCheckStepExportStorage(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepStorageValue($logger)->run();
    }

    /**
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function runCheckStepExportSearch(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepExportSearch($logger)->run();
    }

    /**
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function runCheckStepInstallDemoData(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepInstallTestData($logger)->run();
    }

    /**
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function runCheckStepSetupInstall(LoggerInterface $logger = null)
    {
        $this->getFactory()->createCheckStepSetupInstall($logger)->run();
    }

    /**
     * @api
     *
     * @param string $pathInfo
     *
     * @return array
     */
    public function buildNavigation($pathInfo)
    {
        return $this->getFactory()->createNavigationBuilder()->build($pathInfo);
    }

    /**
     * @api
     *
     * @return void
     */
    public function writeNavigationCache()
    {
        $this->getFactory()->createNavigationCacheBuilder()->writeNavigationCache();
    }

}
