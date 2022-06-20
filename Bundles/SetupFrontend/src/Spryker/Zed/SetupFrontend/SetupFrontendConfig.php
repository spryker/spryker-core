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
     * @var int
     */
    protected const NODE_JS_MINIMUM_REQUIRED_MAJOR_VERSION = 12;

    /**
     * @api
     *
     * @return array<string>
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
     * @return array<string>
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
     * @deprecated Use {@link getYvesInstallMultiPathDirectoryPatterns()} instead.
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
     * @deprecated Use {@link getProjectInstallCommand()} instead.
     *
     * @return array<string>
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
     * @deprecated Use {@link getProjectInstallCommand()} instead.
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
     * @return array<string>
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
     * @deprecated Use {@link getZedInstallMultiPathDirectoryPatterns()} instead.
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
     * @deprecated Use {@link getProjectInstallCommand()} instead.
     *
     * @return array<string>
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
     * @deprecated Use {@link getProjectInstallCommand()} instead.
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

    /**
     * @api
     *
     * @deprecated Use {@link getProjectInstallCommand()} instead.
     *
     * @return string
     */
    public function getMerchantPortalInstallCommand(): string
    {
        return 'yarn install';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantPortalBuildCommand(): string
    {
        return 'npm run mp:build';
    }

    /**
     * Specification:
     * - Used to define minimum required node js version, e.g.: 12.0.0.
     * - Used to download respective node js package for update, e.g.: https://deb.nodesource.com/setup_12.x.
     *
     * @api
     *
     * @return int
     */
    public function getNodeJsMinimumRequiredMajorVersion(): int
    {
        return static::NODE_JS_MINIMUM_REQUIRED_MAJOR_VERSION;
    }
}
