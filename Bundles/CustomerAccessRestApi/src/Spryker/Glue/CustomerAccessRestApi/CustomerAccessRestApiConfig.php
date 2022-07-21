<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomerAccessRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CustomerAccessRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_CUSTOMER_ACCESS = 'customer-access';

    /**
     * @var array<string, array<string>>
     */
    protected const CUSTOMER_ACCESS_CONTENT_TYPE_TO_RESOURCE_TYPE_MAPPING = [];

    /**
     * Specification:
     *  - Returns array that provides mapping between customer access content types and rest resource names.
     *
     * @api
     *
     * @return array<string, mixed>
     */
    public function getCustomerAccessContentTypeToResourceTypeMapping(): array
    {
        return static::CUSTOMER_ACCESS_CONTENT_TYPE_TO_RESOURCE_TYPE_MAPPING;
    }
}
