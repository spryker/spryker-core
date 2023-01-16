<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductListSearch;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductListSearchConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_LIST_DATA = 'PLUGIN_PRODUCT_LIST_DATA';

    /**
     * Specification:
     * - This event is used for product list publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_LIST_PUBLISH = 'ProductList.spy_product_list.publish';

    /**
     * Specification:
     *  - Product list resource name, used for key generation.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_LIST_RESOURCE_NAME = 'product_list_search';
}
