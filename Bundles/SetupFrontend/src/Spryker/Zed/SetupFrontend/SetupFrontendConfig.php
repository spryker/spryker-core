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
    public const DEFAULT_PROCESS_TIMEOUT = 60;

    protected const PROCESS_TIMEOUT = 60;

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
     * @return array
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

    /**
     * Specification:
     * - Returns the value for the process timeout in seconds, after which an exception will be thrown.
     * - Can return 0, 0.0 or null to disable timeout.
     *
     * @return int|float|null
     */
    public function getProcessTimeout()
    {
        return $this->get(static::PROCESS_TIMEOUT);
    }
}
