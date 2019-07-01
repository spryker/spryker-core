<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business;

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
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function buildYvesFrontend(LoggerInterface $logger);

    /**
     * Specification:
     * - Installs needed Yves dependencies.
     *
     * @api
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
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function installZedDependencies(LoggerInterface $logger);

    /**
     * Specification:
     * - Runs Zed frontend builder.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function buildZedFrontend(LoggerInterface $logger);

    /**
     * Specification:
     * - Removes Zed assets.
     *
     * @api
     *
     * @return bool
     */
    public function removeZedAssets();
}
