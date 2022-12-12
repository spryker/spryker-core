<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCodeFlow\Persistence;

use Orm\Zed\OauthCodeFlow\Persistence\SpyOauthCodeFlowAuthCodeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\OauthCodeFlow\Persistence\Propel\Mapper\AuthCodeMapper;

/**
 * @method \Spryker\Zed\OauthCodeFlow\OauthCodeFlowConfig getConfig()
 * @method \Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowRepositoryInterface getRepository()
 */
class OauthCodeFlowPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\OauthCodeFlow\Persistence\SpyOauthCodeFlowAuthCodeQuery
     */
    public function createAuthCodeQuery(): SpyOauthCodeFlowAuthCodeQuery
    {
        return SpyOauthCodeFlowAuthCodeQuery::create();
    }

    /**
     * @return \Spryker\Zed\OauthCodeFlow\Persistence\Propel\Mapper\AuthCodeMapper
     */
    public function createAuthCodeMapper(): AuthCodeMapper
    {
        return new AuthCodeMapper();
    }
}
