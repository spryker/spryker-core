<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SetupFrontend\Business\SetupFrontendBusinessFactory getFactory()
 */
class SetupFrontendFacade extends AbstractFacade implements SetupFrontendFacadeInterface
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function buildYvesFrontend(LoggerInterface $logger)
    {
        return $this->getFactory()->createYvesBuilder()->build($logger);
    }

    /**
     * {@inheritdoc}
     *
     * @api
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function buildZedFrontend(LoggerInterface $logger)
    {
        return $this->getFactory()->createZedBuilder()->build($logger);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return bool
     */
    public function removeZedAssets()
    {
        return $this->getFactory()->createZedAssetsCleaner()->clean();
    }
}
