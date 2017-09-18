<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Dependency;

use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;

interface ProductCategoryEvents
{

    const PRODUCT_CATEGORY_ASSIGNED = 'ProductCategory.product.assigned';
    const PRODUCT_CATEGORY_UNASSIGNED = 'ProductCategory.product.unassigned';

    const PRODUCT_CATEGORY_ABSTRACT_PUBLISH = 'ProductCategory.productAbstract.publish';
    const PRODUCT_CATEGORY_ABSTRACT_UNPUBLISH = 'ProductCategory.productAbstract.unpublish';

    const ENTITY_SPY_PRODUCT_CATEGORY_CREATE = 'Entity.' . SpyProductCategoryTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_CATEGORY_UPDATE = 'Entity.' . SpyProductCategoryTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_CATEGORY_DELETE = 'Entity.' . SpyProductCategoryTableMap::TABLE_NAME . '.delete';

}
