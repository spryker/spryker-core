<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PermissionExtension\Dependency\Plugin;

interface PermissionPluginInterface
{
    /**
     * Specification:
     * - Defines a permission plugin
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string;
}
