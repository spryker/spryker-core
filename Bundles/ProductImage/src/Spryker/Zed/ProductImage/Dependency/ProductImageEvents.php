<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Dependency;

use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetToProductImageTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;

interface ProductImageEvents
{

    const PRODUCT_IMAGE_ABSTRACT_PUBLISH = 'ProductImage.productAbstract.publish';
    const PRODUCT_IMAGE_ABSTRACT_UNPUBLISH = 'ProductImage.productAbstract.unpublish';

    const PRODUCT_IMAGE_PRODUCT_PUBLISH = 'ProductImage.product.publish';
    const PRODUCT_IMAGE_PRODUCT_UNPUBLISH = 'ProductImage.product.unpublish';

    const ENTITY_SPY_PRODUCT_IMAGE_CREATE = 'Entity.' . SpyProductImageTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_IMAGE_UPDATE = 'Entity.' . SpyProductImageTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_IMAGE_DELETE = 'Entity.' . SpyProductImageTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE = 'Entity.' . SpyProductImageSetTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE = 'Entity.' . SpyProductImageSetTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE = 'Entity.' . SpyProductImageSetTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_CREATE = 'Entity.' . SpyProductImageSetToProductImageTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE = 'Entity.' . SpyProductImageSetToProductImageTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE = 'Entity.' . SpyProductImageSetToProductImageTableMap::TABLE_NAME . '.delete';

}
