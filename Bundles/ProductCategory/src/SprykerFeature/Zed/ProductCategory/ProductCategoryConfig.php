<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class ProductCategoryConfig extends AbstractBundleConfig
{
    const PARAM_ID_CATEGORY = 'id-category';
    const PARAM_ID_PARENT_NODE = 'id-parent-node';

    const RESOURCE_TYPE_CATEGORY_NODE = 'categorynode';
    const RESOURCE_TYPE_PRODUCT = 'product';
    const RESOURCE_TYPE_ABSTRACT_PRODUCT = 'abstract_product';
    const RESOURCE_TYPE_URL = 'url';

}
