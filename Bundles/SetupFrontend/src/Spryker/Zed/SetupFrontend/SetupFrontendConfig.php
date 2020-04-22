<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SetupFrontendConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string[]
     */
    public function getProjectFrontendDependencyDirectories()
    {
        return [
            APPLICATION_ROOT_DIR . '/node_modules',
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProjectInstallCommand()
    {
        return 'npm ci --prefer-offline';
    }

    /**
     * Specification:
     * - Returns the public directory for Yves assets.
     *
     * @api
     *
     * @return string[]
     */
    public function getYvesAssetsDirectories()
    {
        return [
            APPLICATION_ROOT_DIR . '/public/Yves/assets',
        ];
    }

    /**
     * @api
     *
     * @deprecated use getYvesInstallMultiPathDirectoryPatterns() instead.
     *
     * @return string
     */
    public function getYvesInstallerDirectoryPattern()
    {
        return $this->get(KernelConstants::SPRYKER_ROOT) . '/*/assets/Yves';
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getYvesInstallMultiPathDirectoryPatterns(): array
    {
        return [
            $this->getYvesInstallerDirectoryPattern(),
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getYvesInstallCommand()
    {
        return 'npm ci --prefer-offline';
    }

    /**
     * Specification:
     * - Returns the command to build Yves assets.
     *
     * @api
     *
     * @return string
     */
    public function getYvesBuildCommand()
    {
        return 'npm run yves';
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getZedAssetsDirectories()
    {
        return [
            APPLICATION_ROOT_DIR . '/public/Zed/assets',
        ];
    }

    /**
     * @api
     *
     * @deprecated use getZedInstallMultiPathDirectoryPatterns() instead.
     *
     * @return string
     */
    public function getZedInstallerDirectoryPattern()
    {
        return $this->get(KernelConstants::SPRYKER_ROOT) . '/*/assets/Zed';
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getZedInstallMultiPathDirectoryPatterns(): array
    {
        return [
            $this->getZedInstallerDirectoryPattern(),
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getZedInstallCommand()
    {
        return 'npm ci --prefer-offline';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getZedBuildCommand()
    {
        return 'npm run zed';
    }
}
