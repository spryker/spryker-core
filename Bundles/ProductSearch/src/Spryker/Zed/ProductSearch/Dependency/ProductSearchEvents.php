<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Dependency;

use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeTableMap;

interface ProductSearchEvents
{

    const ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_CREATE = 'Entity.' . SpyProductSearchAttributeTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_UPDATE = 'Entity.' . SpyProductSearchAttributeTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_DELETE = 'Entity.' . SpyProductSearchAttributeTableMap::TABLE_NAME . '.delete';

}
