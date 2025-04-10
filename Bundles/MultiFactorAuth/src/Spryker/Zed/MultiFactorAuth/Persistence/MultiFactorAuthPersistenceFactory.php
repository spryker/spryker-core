<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Persistence;

use Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuth;
use Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodes;
use Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodesAttempts;
use Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodesAttemptsQuery;
use Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodesQuery;
use Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MultiFactorAuth\Persistence\Mapper\MultiFactorAuthMapper;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class MultiFactorAuthPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodesQuery
     */
    public function createSpyCustomerMultiFactorAuthCodeQuery(): SpyCustomerMultiFactorAuthCodesQuery
    {
        return SpyCustomerMultiFactorAuthCodesQuery::create();
    }

    /**
     * @return \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthQuery
     */
    public function createSpyCustomerMultiFactorAuthQuery(): SpyCustomerMultiFactorAuthQuery
    {
        return SpyCustomerMultiFactorAuthQuery::create();
    }

    /**
     * @return \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodesAttemptsQuery
     */
    public function createSpyCustomerMultiFactorAuthCodesAttemptsQuery(): SpyCustomerMultiFactorAuthCodesAttemptsQuery
    {
        return SpyCustomerMultiFactorAuthCodesAttemptsQuery::create();
    }

    /**
     * @return \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodes
     */
    public function createSpyCustomerMultiFactorAuthCodeEntity(): SpyCustomerMultiFactorAuthCodes
    {
        return new SpyCustomerMultiFactorAuthCodes();
    }

    /**
     * @return \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuth
     */
    public function createSpyCustomerMultiFactorAuthEntity(): SpyCustomerMultiFactorAuth
    {
        return new SpyCustomerMultiFactorAuth();
    }

    /**
     * @return \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodesAttempts
     */
    public function createSpyCustomerMultiFactorAuthCodesAttemptsEntity(): SpyCustomerMultiFactorAuthCodesAttempts
    {
        return new SpyCustomerMultiFactorAuthCodesAttempts();
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Persistence\Mapper\MultiFactorAuthMapper
     */
    public function createMultiFactorAuthMapper(): MultiFactorAuthMapper
    {
        return new MultiFactorAuthMapper();
    }
}
