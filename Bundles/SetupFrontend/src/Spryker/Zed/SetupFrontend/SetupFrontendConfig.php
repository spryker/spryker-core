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
     * @return string[]
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
     * @return string[]
     */
    public function getYvesAssetsDirectories()
    {
        return [
            APPLICATION_ROOT_DIR . '/public/Yves/assets',
        ];
    }

    /**
     * @return string
     */
    public function getYvesInstallerDirectoryPattern()
    {
        return $this->get(KernelConstants::SPRYKER_ROOT) . '/*/assets/Yves';
    }

    /**
     * @return string[]
     */
    public function getYvesInstallMultiPathDirectoryPatterns(): array
    {
        return [
            $this->getYvesInstallerDirectoryPattern(),
        ];
    }

    /**
     * @return string
     */
    public function getYvesInstallCommand()
    {
        return 'npm i --prefer-offline';
    }

    /**
     * @return string
     */
    public function getYvesBuildCommand()
    {
        return 'npm run yves';
    }

    /**
     * @return string[]
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
     * @return string[]
     */
    public function getZedInstallMultiPathDirectoryPatterns(): array
    {
        return [
            $this->getZedInstallerDirectoryPattern(),
            APPLICATION_ROOT_DIR . '/src/Pyz/Zed/*/assets/Zed',
        ];
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
