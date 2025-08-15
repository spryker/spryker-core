<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\InstallerExtension\Dependency\Plugin;

/**
 * Provides extension capabilities for setup:init-db command.
 *
 * Use this plugin if some default/predefined database entities need to be created on the initial deployment.
 * The plugin is supposed to be run once during the database lifetime.
 *
 * Do not use this plugin to interfere with file system.
 */
interface InstallerPluginInterface
{
    /**
     * Specification:
     * - Use to install required data.
     *
     * @api
     *
     * @return void
     */
    public function install();
}
