<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CategoriesRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CATEGORIES = 'category-trees';
    public const RESOURCE_CATEGORY = 'category-nodes';

    public const CONTROLLER_CATEGORIES = 'category-tree-resource';
    public const CONTROLLER_CATEGORY = 'category-resource';

    public const RESPONSE_CODE_INVALID_CATEGORY_ID = '501';
    public const RESPONSE_DETAILS_INVALID_CATEGORY_ID = 'Can\'t find category node with the given id.';
}
