<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Dependency;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelLocalizedAttributesTableMap;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;

interface ProductLabelEvents
{

    const PRODUCT_LABEL_ABSTRACT_PUBLISH = 'ProductLabel.productAbstract.publish';
    const PRODUCT_LABEL_ABSTRACT_UNPUBLISH = 'ProductLabel.productAbstract.unpublish';

    const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_CREATE = 'Entity.' . SpyProductLabelProductAbstractTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_UPDATE = 'Entity.' . SpyProductLabelProductAbstractTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_DELETE = 'Entity.' . SpyProductLabelProductAbstractTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_LABEL_CREATE = 'Entity.' . SpyProductLabelTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_LABEL_UPDATE = 'Entity.' . SpyProductLabelTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_LABEL_DELETE = 'Entity.' . SpyProductLabelTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_CREATE = 'Entity.' . SpyProductLabelLocalizedAttributesTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_UPDATE = 'Entity.' . SpyProductLabelLocalizedAttributesTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_DELETE = 'Entity.' . SpyProductLabelLocalizedAttributesTableMap::TABLE_NAME . '.delete';

}
