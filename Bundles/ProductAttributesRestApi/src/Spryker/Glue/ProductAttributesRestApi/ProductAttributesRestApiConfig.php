<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductAttributesRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_PRODUCT_MANAGEMENT_ATTRIBUTES = 'product-management-attributes';

    public const RESPONSE_CODE_PRODUCT_ATTRIBUTE_NOT_FOUND = '4201';

    public const EXCEPTION_MESSAGE_PRODUCT_ATTRIBUTE_NOT_FOUND = 'Attribute not found.';
}
