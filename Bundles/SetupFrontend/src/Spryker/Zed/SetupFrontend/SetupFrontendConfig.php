<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend;

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
        return 'yarn install';
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
     * @return string
     */
    public function getZedBuildCommand()
    {
        return 'npm run zed';
    }
}
