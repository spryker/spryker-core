<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntityExtension\Dependency\Plugin;

/**
 * Provides capability to enable AclEntity behaviour
 */
interface AclEntityEnablerPluginInterface
{
    /**
     * Specification:
     * - Enables AclEntity behaviour.
     *
     * @api
     *
     * @return bool
     */
    public function isEnabled(): bool;
}
