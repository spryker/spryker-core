<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\SetupFrontend\SetupFrontendConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SetupFrontendConfig extends AbstractBundleConfig
{
    public const YVES_ASSETS_CONFIG_STORE_NAME_KEY = 'name';

    /**
     * @return array
     */
    public function getProjectFrontendDependencyDirectories()
    {
        return [
            APPLICATION_ROOT_DIR . '/node_modules',
        ];
    }

    /**
     * @return string
     */
    public function getProjectInstallCommand()
    {
        return 'npm i --prefer-offline';
    }

    /**
     * Specification:
     * - Yves public folder for assets.
     * - %store% will be replaced with current store.
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
     * Specification:
     * - Yves assets builder config file path.
     *
     * @api
     *
     * @return string
     */
    public function getYvesFrontendConfigFilePath(): string
    {
        return APPLICATION_ROOT_DIR . '/frontend/config.json';
    }

    /**
     * @return string
     */
    public function getYvesInstallerDirectoryPattern()
    {
        return $this->get(KernelConstants::SPRYKER_ROOT) . '/*/assets/Yves';
    }

    /**
     * @return string
     */
    public function getYvesInstallCommand()
    {
        return 'npm i --prefer-offline';
    }

    /**
     * Specification:
     * - Yves assets builder command.
     *
     * @api
     *
     * @return string
     */
    public function getYvesBuildCommand()
    {
        return $this->get(SetupFrontendConstants::YVES_BUILD_COMMAND, 'npm run yves');
    }

    /**
     * @return array
     */
    public function getZedAssetsDirectories()
    {
        return [
            APPLICATION_ROOT_DIR . '/public/Zed/assets',
        ];
    }

    /**
     * @return string
     */
    public function getZedInstallerDirectoryPattern()
    {
        return $this->get(KernelConstants::SPRYKER_ROOT) . '/*/assets/Zed';
    }

    /**
     * @return string
     */
    public function getZedInstallCommand()
    {
        return 'npm i --prefer-offline';
    }

    /**
     * @return string
     */
    public function getZedBuildCommand()
    {
        return 'npm run zed';
    }
}
