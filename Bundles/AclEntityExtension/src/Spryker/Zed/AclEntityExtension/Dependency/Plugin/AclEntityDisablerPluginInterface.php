<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntityExtension\Dependency\Plugin;

/**
 * Provides capability to disable AclEntity behaviour
 */
interface AclEntityDisablerPluginInterface
{
    /**
     * Specification:
     * - Disables `AclEntity` behaviour.
     *
     * @api
     *
     * @return bool
     */
    public function isDisabled(): bool;
}
