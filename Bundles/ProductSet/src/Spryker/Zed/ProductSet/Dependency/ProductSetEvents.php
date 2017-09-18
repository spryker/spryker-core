<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Dependency;

use Orm\Zed\ProductSet\Persistence\Map\SpyProductAbstractSetTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetDataTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetTableMap;

interface ProductSetEvents
{

    const PRODUCT_SET_PUBLISH = 'ProductSet.entity.publish';
    const PRODUCT_SET_UNPUBLISH = 'ProductSet.entity.unpublish';

    const ENTITY_SPY_PRODUCT_SET_CREATE = 'Entity.' . SpyProductSetTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_SET_UPDATE = 'Entity.' . SpyProductSetTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_SET_DELETE = 'Entity.' . SpyProductSetTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_ABSTRACT_SET_CREATE = 'Entity.' . SpyProductAbstractSetTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_ABSTRACT_SET_UPDATE = 'Entity.' . SpyProductAbstractSetTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_ABSTRACT_SET_DELETE = 'Entity.' . SpyProductAbstractSetTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_SET_DATA_CREATE = 'Entity.' . SpyProductSetDataTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_SET_DATA_UPDATE = 'Entity.' . SpyProductSetDataTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_SET_DATA_DELETE = 'Entity.' . SpyProductSetDataTableMap::TABLE_NAME . '.delete';

}
