<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Permission\Persistence\SpyPermissionQuery;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUserQuery;
use Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroupQuery;
use Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroupToPermissionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SharedCart\Persistence\Propel\Mapper\CustomerMapper;
use Spryker\Zed\SharedCart\Persistence\Propel\Mapper\QuoteCompanyUserMapper;
use Spryker\Zed\SharedCart\Persistence\Propel\Mapper\QuotePermissionGroupMapper;
use Spryker\Zed\SharedCart\Persistence\Propel\Mapper\QuotePermissionGroupMapperInterface;
use Spryker\Zed\SharedCart\Persistence\Propel\Mapper\QuoteShareDetailMapper;
use Spryker\Zed\SharedCart\Persistence\Propel\Mapper\QuoteShareDetailMapperInterface;

/**
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface getRepository()
 */
class SharedCartPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Permission\Persistence\SpyPermissionQuery
     */
    public function createPermissionQuery(): SpyPermissionQuery
    {
        return SpyPermissionQuery::create();
    }

    /**
     * @return \Orm\Zed\Quote\Persistence\SpyQuoteQuery
     */
    public function createQuoteQuery(): SpyQuoteQuery
    {
        return SpyQuoteQuery::create();
    }

    /**
     * @return \Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroupQuery
     */
    public function createQuotePermissionGroupQuery(): SpyQuotePermissionGroupQuery
    {
        return SpyQuotePermissionGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUserQuery
     */
    public function createQuoteCompanyUserQuery(): SpyQuoteCompanyUserQuery
    {
        return SpyQuoteCompanyUserQuery::create();
    }

    /**
     * @return \Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroupToPermissionQuery
     */
    public function createQuotePermissionGroupToPermissionQuery(): SpyQuotePermissionGroupToPermissionQuery
    {
        return SpyQuotePermissionGroupToPermissionQuery::create();
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    public function createCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function createSpyCustomerQuery(): SpyCustomerQuery
    {
        return SpyCustomerQuery::create();
    }

    /**
     * @return \Spryker\Zed\SharedCart\Persistence\Propel\Mapper\QuotePermissionGroupMapperInterface
     */
    public function createQuotePermissionGroupMapper(): QuotePermissionGroupMapperInterface
    {
        return new QuotePermissionGroupMapper();
    }

    /**
     * @return \Spryker\Zed\SharedCart\Persistence\Propel\Mapper\QuoteShareDetailMapperInterface
     */
    public function createQuoteShareDetailMapper(): QuoteShareDetailMapperInterface
    {
        return new QuoteShareDetailMapper();
    }

    /**
     * @return \Spryker\Zed\SharedCart\Persistence\Propel\Mapper\QuoteCompanyUserMapper
     */
    public function createQuoteCompanyUserMapper(): QuoteCompanyUserMapper
    {
        return new QuoteCompanyUserMapper();
    }

    /**
     * @return \Spryker\Zed\SharedCart\Persistence\Propel\Mapper\CustomerMapper
     */
    public function createCustomerMapper(): CustomerMapper
    {
        return new CustomerMapper();
    }
}
