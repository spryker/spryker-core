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

    public const RESPONSE_CODE_UNAUTHORIZED_ACCESS_FORBIDDEN = '3001';
    public const RESPONSE_MESSAGE_UNAUTHORIZED_ACCESS_FORBIDDEN = 'You have to login to access resource.';

    protected const RESOURCE_TYPE_TO_PERMISSION_PLUGIN_MAPPING = [];
    protected const CUSTOMER_ACCESS_CONTENT_TYPE_TO_RESOURCE_TYPE_MAPPING = [];

    /**
     * Specification:
     *  - Returns permission plugin key if passed resource type has a mapping for it.
     *
     * @api
     *
     * @param string $resourceType
     *
     * @return string|null
     */
    public function findPermissionPluginNameByResourceType(string $resourceType): ?string
    {
        return static::RESOURCE_TYPE_TO_PERMISSION_PLUGIN_MAPPING[$resourceType] ?? null;
    }

    /**
     * Specification:
     *  - Returns array that provides mapping between customer access content types and rest resource names.
     *
     * @api
     *
     * @return array
     */
    public function getCustomerAccessContentTypeToResourceTypeMapping(): array
    {
        return static::CUSTOMER_ACCESS_CONTENT_TYPE_TO_RESOURCE_TYPE_MAPPING;
    }
}
