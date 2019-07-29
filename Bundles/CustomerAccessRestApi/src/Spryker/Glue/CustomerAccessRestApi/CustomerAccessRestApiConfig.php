<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomerAccessRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CustomerAccessRestApiConfig extends AbstractBundleConfig
{
    protected const RESOURCE_TYPE_PERMISSION_PLUGIN = [];

    /**
     * @param string $resourceType
     *
     * @return bool
     */
    public function hasPluginNameByResourceType(string $resourceType): bool
    {
        return array_key_exists($resourceType, static::RESOURCE_TYPE_PERMISSION_PLUGIN);
    }

    /**
     * @param string $resourceType
     *
     * @return string
     */
    public function getPluginNameByResourceType(string $resourceType): string
    {
        return static::RESOURCE_TYPE_PERMISSION_PLUGIN[$resourceType];
    }
}
