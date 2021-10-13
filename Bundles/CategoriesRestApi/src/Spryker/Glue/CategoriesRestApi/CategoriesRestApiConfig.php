<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CategoriesRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_CATEGORY_TREES_ACTION_NAME = 'get';
    /**
     * @var bool
     */
    public const RESOURCE_CATEGORY_TREES_IS_PROTECTED = false;

    /**
     * @var string
     */
    public const RESOURCE_CATEGORY_NODES_ACTION_NAME = 'get';
    /**
     * @var bool
     */
    public const RESOURCE_CATEGORY_NODES_IS_PROTECTED = false;

    /**
     * @var string
     */
    public const RESOURCE_PRODUCT_CATEGORIES_ACTION_NAME = 'get';

    /**
     * @var string
     */
    public const RESOURCE_CATEGORY_TREES = 'category-trees';
    /**
     * @var string
     */
    public const RESOURCE_CATEGORY_NODES = 'category-nodes';

    /**
     * @var string
     */
    public const CONTROLLER_CATEGORIES = 'category-tree-resource';
    /**
     * @var string
     */
    public const CONTROLLER_CATEGORY = 'category-resource';
    /**
     * @var string
     */
    public const CONTROLLER_PRODUCT_CATEGORIES = 'product-categories-resource';

    /**
     * @var string
     */
    public const RESPONSE_CODE_INVALID_CATEGORY_ID = '701';
    /**
     * @var string
     */
    public const RESPONSE_CODE_ABSTRACT_PRODUCT_CATEGORIES_ARE_MISSING = '702';
    /**
     * @var string
     */
    public const RESPONSE_CODE_CATEGORY_NOT_FOUND = '703';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_INVALID_CATEGORY_ID = 'Category node id has not been specified or invalid.';
    /**
     * @var string
     */
    public const RESPONSE_DETAILS_ABSTRACT_PRODUCT_CATEGORIES_ARE_MISSING = 'Can\'t find product categories by requested SKU.';
    /**
     * @var string
     */
    public const RESPONSE_DETAILS_CATEGORY_NOT_FOUND = 'Can\'t find category node with the given id.';
}
