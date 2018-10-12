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
     * @return array
     */
    public function getProjectFrontendDependencyDirectories(): array
    {
        return [
            APPLICATION_ROOT_DIR . '/node_modules',
        ];
    }

    /**
     * @return string
     */
    public function getProjectInstallCommand(): string
    {
        return 'npm i --prefer-offline';
    }

    /**
     * @return array
     */
    public function getYvesAssetsDirectories(): array
    {
        return [
            APPLICATION_ROOT_DIR . '/public/Yves/assets',
        ];
    }

    /**
     * @return array
     */
    public function getYvesInstallerDirectoryPattern(): array
    {
        return [
            $this->get(KernelConstants::SPRYKER_ROOT) . '/*/assets/Yves',
        ];
    }

    /**
     * @return string
     */
    public function getYvesInstallCommand(): string
    {
        return 'npm i --prefer-offline';
    }

    /**
     * @return string
     */
    public function getYvesBuildCommand(): string
    {
        return 'npm run yves';
    }

    /**
     * @return array
     */
    public function getZedAssetsDirectories(): array
    {
        return [
            APPLICATION_ROOT_DIR . '/public/Zed/assets',
        ];
    }

    /**
     * @return array
     */
    public function getZedInstallerDirectoryPattern(): array
    {
        return [
            $this->get(KernelConstants::SPRYKER_ROOT) . '/*/assets/Zed',
        ];
    }

    /**
     * @return string
     */
    public function getZedInstallCommand(): string
    {
        return 'npm i --prefer-offline';
    }

    /**
     * @return string
     */
    public function getZedBuildCommand(): string
    {
        return 'npm run zed';
    }
}
