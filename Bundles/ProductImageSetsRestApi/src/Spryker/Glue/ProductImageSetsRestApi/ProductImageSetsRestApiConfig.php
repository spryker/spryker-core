<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductImageSetsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_ABSTRACT_PRODUCT_IMAGE_SETS = 'abstract-product-image-sets';
    public const RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS = 'concrete-product-image-sets';

    public const RESPONSE_CODE_ABSTRACT_PRODUCT_IMAGE_SETS_NOT_FOUND = '303';
    public const RESPONSE_DETAIL_ABSTRACT_PRODUCT_IMAGE_SETS_NOT_FOUND = 'Can`t find abstract product image sets.';

    public const RESPONSE_CODE_CONCRETE_PRODUCT_IMAGE_SETS_NOT_FOUND = '304';
    public const RESPONSE_DETAIL_CONCRETE_PRODUCT_IMAGE_SETS_NOT_FOUND = 'Can`t find concrete product image sets.';
}
