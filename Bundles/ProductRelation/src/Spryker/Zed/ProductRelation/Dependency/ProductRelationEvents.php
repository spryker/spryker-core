<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Dependency;

use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;

interface ProductRelationEvents
{

    const PRODUCT_RELATION_ABSTRACT_PUBLISH = 'ProductRelation.productAbstract.publish';
    const PRODUCT_RELATION_ABSTRACT_UNPUBLISH = 'ProductRelation.productAbstract.unpublish';

    const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_CREATE = 'Entity.' . SpyProductRelationProductAbstractTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_UPDATE = 'Entity.' . SpyProductRelationProductAbstractTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_DELETE = 'Entity.' . SpyProductRelationProductAbstractTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_RELATION_CREATE = 'Entity.' . SpyProductRelationTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_RELATION_UPDATE = 'Entity.' . SpyProductRelationTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_RELATION_DELETE = 'Entity.' . SpyProductRelationTableMap::TABLE_NAME . '.delete';

}
