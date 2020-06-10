<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business;

use Generated\Shared\Transfer\SetupFrontendConfigurationTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SetupFrontend\Business\SetupFrontendBusinessFactory getFactory()
 */
class SetupFrontendFacade extends AbstractFacade implements SetupFrontendFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function installPackageManager(LoggerInterface $logger)
    {
        return $this->getFactory()->createPackageManagerInstaller()->install($logger);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function installProjectDependencies(LoggerInterface $logger)
    {
        return $this->getFactory()->createProjectInstaller()->install($logger);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function cleanupProjectDependencies()
    {
        return $this->getFactory()->createProjectDependencyCleaner()->clean();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Generated\Shared\Transfer\SetupFrontendConfigurationTransfer|null $setupFrontendConfigurationTransfer
     *
     * @return bool
     */
    public function buildYvesFrontend(LoggerInterface $logger, ?SetupFrontendConfigurationTransfer $setupFrontendConfigurationTransfer = null)
    {
        return $this->getFactory()->createYvesBuilder()->build($logger, $setupFrontendConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated In next major all dependencies will be installed via single command {@see $this->installProjectDependencies()}
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function installYvesDependencies(LoggerInterface $logger)
    {
        return $this->getFactory()->createYvesDependencyInstaller()->install($logger);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function removeYvesAssets()
    {
        return $this->getFactory()->createYvesAssetsCleaner()->clean();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated In next major all dependencies will be installed via single command {@see $this->installProjectDependencies()}
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function installZedDependencies(LoggerInterface $logger)
    {
        return $this->getFactory()->createZedDependencyInstaller()->install($logger);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Generated\Shared\Transfer\SetupFrontendConfigurationTransfer|null $setupFrontendConfigurationTransfer
     *
     * @return bool
     */
    public function buildZedFrontend(LoggerInterface $logger, ?SetupFrontendConfigurationTransfer $setupFrontendConfigurationTransfer = null)
    {
        return $this->getFactory()->createZedBuilder()->build($logger, $setupFrontendConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function removeZedAssets()
    {
        return $this->getFactory()->createZedAssetsCleaner()->clean();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated In next major all dependencies will be installed via single command {@see $this->installProjectDependencies()}
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function installMerchantPortalDependencies(LoggerInterface $logger): bool
    {
        return $this->getFactory()->createMerchantPortalDependencyInstaller()->install($logger);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function buildMerchantPortalFrontend(LoggerInterface $logger): bool
    {
        return $this->getFactory()->createMerchantPortalBuilder()->build($logger);
    }
}
