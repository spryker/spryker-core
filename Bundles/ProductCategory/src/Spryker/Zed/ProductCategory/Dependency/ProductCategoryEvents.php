<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Dependency;

interface ProductCategoryEvents
{
    const PRODUCT_CATEGORY_ASSIGNED = 'ProductCategory.product.assigned';
    const PRODUCT_CATEGORY_UNASSIGNED = 'ProductCategory.product.unassigned';
}
