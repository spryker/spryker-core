<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 */
class ProductManagementPersistenceFactory extends AbstractPersistenceFactory
{
    //to be removed

    /**
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery
     */
    public function createProductManagementAttributeQuery()
    {
        return SpyProductManagementAttributeQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function createProductManagementAttributeValueQuery()
    {
        return SpyProductManagementAttributeValueQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function createProductAttributeKeyQuery()
    {
        return SpyProductAttributeKeyQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslationQuery
     */
    public function createProductManagementAttributeValueTranslationQuery()
    {
        return SpyProductManagementAttributeValueTranslationQuery::create();
    }
}
