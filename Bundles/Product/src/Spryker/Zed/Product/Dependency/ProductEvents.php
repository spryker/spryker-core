<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;

interface ProductEvents
{

    const PRODUCT_ABSTRACT_BEFORE_CREATE = 'Product.product_abstract.before.create';
    const PRODUCT_ABSTRACT_BEFORE_UPDATE = 'Product.product_abstract.before.update';

    const PRODUCT_CONCRETE_BEFORE_CREATE = 'Product.product_concrete.before.create';
    const PRODUCT_CONCRETE_BEFORE_UPDATE = 'Product.product_concrete.before.update';

    const PRODUCT_ABSTRACT_AFTER_UPDATE = 'Product.product_abstract.after.update';
    const PRODUCT_ABSTRACT_AFTER_CREATE = 'Product.product_abstract.after.create';

    const PRODUCT_CONCRETE_AFTER_CREATE = 'Product.product_concrete.after.create';
    const PRODUCT_CONCRETE_AFTER_UPDATE = 'Product.product_concrete.after.update';

    const PRODUCT_ABSTRACT_READ = 'Product.product_abstract.read';
    const PRODUCT_CONCRETE_READ = 'Product.product_concrete.read';

    const PRODUCT_ABSTRACT_PUBLISH = 'Product.product_abstract.publish';
    const PRODUCT_ABSTRACT_REFRESH = 'Product.product_abstract.refresh';
    const PRODUCT_ABSTRACT_UNPUBLISH = 'Product.product_abstract.unpublish';

    const PRODUCT_PUBLISH = 'Product.product.publish';
    const PRODUCT_REFRESH = 'Product.product.refresh';
    const PRODUCT_UNPUBLISH = 'Product.product.unpublish';

    const ENTITY_SPY_PRODUCT_CREATE = 'Entity.' . SpyProductTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_UPDATE = 'Entity.' . SpyProductTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_DELETE = 'Entity.' . SpyProductTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE = 'Entity.' . SpyProductLocalizedAttributesTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE = 'Entity.' . SpyProductLocalizedAttributesTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_DELETE = 'Entity.' . SpyProductLocalizedAttributesTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_ABSTRACT_CREATE = 'Entity.' . SpyProductAbstractTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE = 'Entity.' . SpyProductAbstractTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_ABSTRACT_DELETE = 'Entity.' . SpyProductAbstractTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_CREATE = 'Entity.' . SpyProductAbstractLocalizedAttributesTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE = 'Entity.' . SpyProductAbstractLocalizedAttributesTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_DELETE = 'Entity.' . SpyProductAbstractLocalizedAttributesTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_CREATE = 'Entity.' . SpyProductAttributeKeyTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_UPDATE = 'Entity.' . SpyProductAttributeKeyTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_DELETE = 'Entity.' . SpyProductAttributeKeyTableMap::TABLE_NAME . '.delete';

}
