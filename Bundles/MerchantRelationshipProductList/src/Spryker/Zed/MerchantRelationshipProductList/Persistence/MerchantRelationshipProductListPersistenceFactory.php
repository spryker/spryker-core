<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Persistence;

use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantRelationshipProductList\MerchantRelationshipProductListDependencyProvider;
use Spryker\Zed\MerchantRelationshipProductList\Persistence\Propel\Mapper\MerchantRelationshipProductListMapper;
use Spryker\Zed\MerchantRelationshipProductList\Persistence\Propel\Mapper\MerchantRelationshipProductListMapperInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductList\MerchantRelationshipProductListConfig getConfig()
 */
class MerchantRelationshipProductListPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    public function getProductListQuery(): SpyProductListQuery
    {
        return $this->getProvidedDependency(MerchantRelationshipProductListDependencyProvider::PROPEL_QUERY_PRODUCT_LIST);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductList\Persistence\Propel\Mapper\MerchantRelationshipProductListMapperInterface
     */
    public function createMerchantRelationshipProductListMapper(): MerchantRelationshipProductListMapperInterface
    {
        return new MerchantRelationshipProductListMapper();
    }
}
