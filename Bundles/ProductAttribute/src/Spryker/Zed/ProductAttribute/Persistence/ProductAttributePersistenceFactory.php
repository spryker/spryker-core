<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductAttribute\Persistence\Mapper\ProductAttributeMapper;
use Spryker\Zed\ProductAttribute\Persistence\Mapper\ProductAttributeMapperInterface;

/**
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface getQueryContainer()
 */
class ProductAttributePersistenceFactory extends AbstractPersistenceFactory
{
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

    /**
     * @return \Spryker\Zed\ProductAttribute\Persistence\Mapper\ProductAttributeMapperInterface
     */
    public function createProductAttributeMapper(): ProductAttributeMapperInterface
    {
        return new ProductAttributeMapper();
    }
}
