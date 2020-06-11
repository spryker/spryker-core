<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business;

use Generated\Shared\Transfer\SetupFrontendConfigurationTransfer;
use Psr\Log\LoggerInterface;

interface SetupFrontendFacadeInterface
{
    /**
     * Specification:
     * - Installs needed package manager.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function installPackageManager(LoggerInterface $logger);

    /**
     * Specification:
     * - Installs needed project dependencies.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function installProjectDependencies(LoggerInterface $logger);

    /**
     * Specification:
     * - Removes project dependencies.
     *
     * @api
     *
     * @return bool
     */
    public function cleanupProjectDependencies();

    /**
     * Specification:
     * - Runs Yves frontend builder.
     * - For forward compatibility with next major version `SetupFrontendConfigurationTransfer` is used to configure build process.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Generated\Shared\Transfer\SetupFrontendConfigurationTransfer|null $setupFrontendConfigurationTransfer
     *
     * @return bool
     */
    public function buildYvesFrontend(LoggerInterface $logger, ?SetupFrontendConfigurationTransfer $setupFrontendConfigurationTransfer = null);

    /**
     * Specification:
     * - Installs needed Yves dependencies.
     *
     * @api
     *
     * @deprecated In next major all dependencies will be installed via single command {@see $this->installProjectDependencies()}
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function installYvesDependencies(LoggerInterface $logger);

    /**
     * Specification:
     * - Removes Yves assets.
     *
     * @api
     *
     * @return bool
     */
    public function removeYvesAssets();

    /**
     * Specification:
     * - Installs needed Zed dependencies.
     *
     * @api
     *
     * @deprecated In next major all dependencies will be installed via single command {@see $this->installProjectDependencies()}
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function installZedDependencies(LoggerInterface $logger);

    /**
     * Specification:
     * - Runs Zed frontend builder.
     * - For forward compatibility with next major version `SetupFrontendConfigurationTransfer` is used to configure build process.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Generated\Shared\Transfer\SetupFrontendConfigurationTransfer|null $setupFrontendConfigurationTransfer
     *
     * @return bool
     */
    public function buildZedFrontend(LoggerInterface $logger, ?SetupFrontendConfigurationTransfer $setupFrontendConfigurationTransfer = null);

    /**
     * Specification:
     * - Removes Zed assets.
     *
     * @api
     *
     * @return bool
     */
    public function removeZedAssets();

    /**
     * Specification:
     * - Installs needed Merchant Portal dependencies.
     *
     * @api
     *
     * @deprecated In next major all dependencies will be installed via single command {@see $this->installProjectDependencies()}
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function installMerchantPortalDependencies(LoggerInterface $logger): bool;

    /**
     * Specification:
     * - Runs Merchant Portal frontend builder.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function buildMerchantPortalFrontend(LoggerInterface $logger): bool;
}
