<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorageQuery;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Mapper\CompanyBusinessUnitPriceProductMapper;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Mapper\CompanyBusinessUnitPriceProductMapperInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Mapper\PriceProductMerchantRelationship\PriceProductMerchantRelationshipMapper;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig getConfig()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface getRepository()
 */
class PriceProductMerchantRelationshipStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Mapper\CompanyBusinessUnitPriceProductMapperInterface
     */
    public function createCompanyBusinessUnitPriceProductMapper(): CompanyBusinessUnitPriceProductMapperInterface
    {
        return new CompanyBusinessUnitPriceProductMapper();
    }

    /**
     * @return \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery
     */
    public function getPropelPriceProductMerchantRelationshipQuery(): SpyPriceProductMerchantRelationshipQuery
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipStorageDependencyProvider::PROPEL_QUERY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP);
    }

    /**
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorageQuery
     */
    public function createPriceProductConcreteMerchantRelationshipStorageQuery(): SpyPriceProductConcreteMerchantRelationshipStorageQuery
    {
        return SpyPriceProductConcreteMerchantRelationshipStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorageQuery
     */
    public function createPriceProductAbstractMerchantRelationshipStorageQuery(): SpyPriceProductAbstractMerchantRelationshipStorageQuery
    {
        return SpyPriceProductAbstractMerchantRelationshipStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Mapper\PriceProductMerchantRelationship\PriceProductMerchantRelationshipMapper
     */
    public function createPriceProductMerchantRelationshipMapper(): PriceProductMerchantRelationshipMapper
    {
        return new PriceProductMerchantRelationshipMapper();
    }
}
