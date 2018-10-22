<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductLabelsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_PRODUCT_LABELS = 'product-labels';

    public const CONTROLLER_PRODUCT_LABELS = 'product-labels-resource';
    public const ACTION_PRODUCT_LABELS_GET = 'get';

    public const RESPONSE_CODE_CANT_FIND_PRODUCT_LABEL = '1201';
    public const RESPONSE_DETAIL_CANT_FIND_PRODUCT_LABEL = 'Product label is not found.';

    public const RESPONSE_CODE_PRODUCT_LABEL_ID_IS_MISSING = '1202';
    public const RESPONSE_DETAIL_PRODUCT_LABEL_ID_IS_MISSING = 'Product label id is missing.';
}
