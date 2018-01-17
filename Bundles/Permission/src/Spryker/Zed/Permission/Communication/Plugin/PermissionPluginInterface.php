<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Communication\Plugin;

interface PermissionPluginInterface
{
    /**
     * Specification:
     * - The is used to identify the permission
     *
     * @return string
     */
    public function getKey();
}
