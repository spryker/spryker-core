<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission;

use Spryker\Client\CustomerAccessPermission\Exception\PermissionPluginNotFoundException;
use Spryker\Client\Kernel\AbstractBundleConfig;

class CustomerAccessPermissionConfig extends AbstractBundleConfig
{

    protected const CONTENT_PERMISSION_PLUGIN = [];
    protected const MESSAGE_PLUGIN_NOT_FOUND_EXCEPTION = 'Plugin not found';

    /**
     * @param string $contentType
     *
     * @return string
     *
     * @throws PermissionPluginNotFoundException
     */
    public function getPluginNameToSeeContentType(string $contentType): string
    {
        if (!array_key_exists($contentType, static::CONTENT_PERMISSION_PLUGIN)) {
            throw new PermissionPluginNotFoundException(static::MESSAGE_PLUGIN_NOT_FOUND_EXCEPTION);
        }

        return static::CONTENT_PERMISSION_PLUGIN[$contentType];
    }
}
