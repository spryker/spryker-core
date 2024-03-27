<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence;

use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantRelationship\MerchantRelationshipDependencyProvider;
use Spryker\Zed\MerchantRelationship\Persistence\Propel\Mapper\CompanyBusinessUnitMapper;
use Spryker\Zed\MerchantRelationship\Persistence\Propel\Mapper\MerchantRelationshipMapper;

/**
 * @method \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface getRepository()
 */
class MerchantRelationshipPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    public function createMerchantRelationshipQuery(): SpyMerchantRelationshipQuery
    {
        return SpyMerchantRelationshipQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery
     */
    public function createMerchantRelationshipToCompanyBusinessUnitQuery(): SpyMerchantRelationshipToCompanyBusinessUnitQuery
    {
        return SpyMerchantRelationshipToCompanyBusinessUnitQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Persistence\Propel\Mapper\MerchantRelationshipMapper
     */
    public function createPropelMerchantRelationshipMapper(): MerchantRelationshipMapper
    {
        return new MerchantRelationshipMapper();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Persistence\Propel\Mapper\CompanyBusinessUnitMapper
     */
    public function createCompanyBusinessUnitMapper(): CompanyBusinessUnitMapper
    {
        return new CompanyBusinessUnitMapper();
    }

    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    public function getCompanyBusinessUnitPropelQuery(): SpyCompanyBusinessUnitQuery
    {
        return $this->getProvidedDependency(MerchantRelationshipDependencyProvider::PROPEL_QUERY_COMPANY_BUSINESS_UNIT);
    }
}
