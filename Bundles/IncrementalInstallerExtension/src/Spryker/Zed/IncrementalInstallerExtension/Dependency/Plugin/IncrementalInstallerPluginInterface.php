<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IncrementalInstallerExtension\Dependency\Plugin;

interface IncrementalInstallerPluginInterface
{
    /**
     * Specification:
     * - Returns true if the plugin is applicable for the current environment.
     *
     * @api
     *
     * @return bool
     */
    public function isApplicable(): bool;

    /**
     * Specification:
     * - Executes the incremental installer plugin.
     *
     * @api
     *
     * @return void
     */
    public function execute(): void;

    /**
     * Specification:
     * - Rolls back the incremental installer.
     *
     * @api
     *
     * @return void
     */
    public function rollback(): void;
}
