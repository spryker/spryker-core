<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomerAccessRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CustomerAccessRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CUSTOMER_ACCESS = 'customer-access';

    protected const RESOURCE_TYPE_PERMISSION_PLUGIN = [];
    protected const CUSTOMER_ACCESS_CONTENT_TYPE_RESOURCE_TYPE = [];

    /**
     * Specification:
     *  - Returns array that provides mapping between rest resource names and permission plugin keys.
     *
     * @api
     *
     * @param string $resourceType
     *
     * @return string|null
     */
    public function findPermissionPluginNameByResourceType(string $resourceType): ?string
    {
        return static::RESOURCE_TYPE_PERMISSION_PLUGIN[$resourceType] ?? null;
    }

    /**
     * Specification:
     *  - Returns array that provides mapping between customer access content types and rest resource names.
     *
     * @api
     *
     * @return array
     */
    public function getCustomerAccessContentTypeResourceType(): array
    {
        return static::CUSTOMER_ACCESS_CONTENT_TYPE_RESOURCE_TYPE;
    }
}
