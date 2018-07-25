<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CategoriesRestApiConfig extends AbstractBundleConfig
{
    const RESOURCE_CATEGORIES = 'category-trees';
    const RESOURCE_CATEGORY = 'category-nodes';

    const CONTROLLER_CATEGORIES = 'category-tree-resource';
    const CONTROLLER_CATEGORY = 'category-resource';
}
