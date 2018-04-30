<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission;

use Spryker\Client\Kernel\AbstractBundleConfig;

class CustomerAccessPermissionConfig extends AbstractBundleConfig
{

    protected const CONTENT_PERMISSION_PLUGIN = [];

    /**
     * @param string $contentType
     *
     * @return string
     */
    public function getPluginNameToSeeContentType($contentType)
    {
        if (!array_key_exists($contentType, static::CONTENT_PERMISSION_PLUGIN)) {
            throw new \Exception('Plugin not found');
        }

        return static::CONTENT_PERMISSION_PLUGIN[$contentType];
    }
}
