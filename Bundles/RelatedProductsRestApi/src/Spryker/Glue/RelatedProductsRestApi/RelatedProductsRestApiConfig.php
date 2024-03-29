<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RelatedProductsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class RelatedProductsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RELATIONSHIP_NAME_RELATED_PRODUCTS = 'related-products';

    /**
     * @var string
     */
    public const CONTROLLER_RELATED_PRODUCTS = 'related-products';

    /**
     * @var string
     */
    public const ACTION_RELATED_PRODUCTS_GET = 'get';
}
