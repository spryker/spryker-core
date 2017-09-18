<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Dependency;

use Orm\Zed\ProductGroup\Persistence\Map\SpyProductAbstractGroupTableMap;

interface ProductGroupEvents
{

    const PRODUCT_GROUP_ABSTRACT_PUBLISH = 'ProductGroup.productAbstract.publish';
    const PRODUCT_GROUP_ABSTRACT_UNPUBLISH = 'ProductGroup.productAbstract.unpublish';

    const ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_CREATE = 'Entity.' . SpyProductAbstractGroupTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_UPDATE = 'Entity.' . SpyProductAbstractGroupTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_DELETE = 'Entity.' . SpyProductAbstractGroupTableMap::TABLE_NAME . '.delete';

}
