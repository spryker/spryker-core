<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Persistence;

use Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery;
use Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestToCompanyBusinessUnitQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CompanyBusinessUnitMapper;
use Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CompanyMapper;
use Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CompanyUserMapper;
use Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CustomerMapper;
use Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\MerchantMapper;
use Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\MerchantRelationRequestMapper;

/**
 * @method \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface getEntityManager()
 */
class MerchantRelationRequestPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery
     */
    public function getMerchantRelationRequestQuery(): SpyMerchantRelationRequestQuery
    {
        return SpyMerchantRelationRequestQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestToCompanyBusinessUnitQuery
     */
    public function getMerchantRelationRequestToCompanyBusinessUnitQuery(): SpyMerchantRelationRequestToCompanyBusinessUnitQuery
    {
        return SpyMerchantRelationRequestToCompanyBusinessUnitQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\MerchantRelationRequestMapper
     */
    public function createMerchantRelationRequestMapper(): MerchantRelationRequestMapper
    {
        return new MerchantRelationRequestMapper(
            $this->createMerchantMapper(),
            $this->createCompanyUserMapper(),
            $this->createCompanyBusinessUnitMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\MerchantMapper
     */
    public function createMerchantMapper(): MerchantMapper
    {
        return new MerchantMapper();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CompanyUserMapper
     */
    public function createCompanyUserMapper(): CompanyUserMapper
    {
        return new CompanyUserMapper(
            $this->createCustomerMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CompanyBusinessUnitMapper
     */
    public function createCompanyBusinessUnitMapper(): CompanyBusinessUnitMapper
    {
        return new CompanyBusinessUnitMapper(
            $this->createCompanyMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CompanyMapper
     */
    public function createCompanyMapper(): CompanyMapper
    {
        return new CompanyMapper();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CustomerMapper
     */
    public function createCustomerMapper(): CustomerMapper
    {
        return new CustomerMapper();
    }
}
