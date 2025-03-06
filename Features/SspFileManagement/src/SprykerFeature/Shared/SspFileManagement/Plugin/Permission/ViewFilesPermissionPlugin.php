<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Shared\SspFileManagement\Plugin\Permission;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

class ViewFilesPermissionPlugin implements PermissionPluginInterface
{
    /**
     * @var string
     */
    protected const KEY = 'ViewFilesPermissionPlugin';

    /**
     * {@inheritDoc}
     * - Returns plugin name as a key to permission to view files.
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}
