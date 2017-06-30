<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CategoryConfig extends AbstractBundleConfig
{

    /**
     * Used as `item_type` for touch mechanism.
     */
    const RESOURCE_TYPE_CATEGORY_NODE = 'categorynode';

    /**
     * Used as `item_type` for touch mechanism.
     */
    const RESOURCE_TYPE_NAVIGATION = 'navigation';

    /**
     * Default available template for category
     */
    const CATEGORY_TEMPLATE_DEFAULT = 'Category';

}
