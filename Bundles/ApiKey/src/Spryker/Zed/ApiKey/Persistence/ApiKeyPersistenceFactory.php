<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Persistence;

use Orm\Zed\ApiKey\Persistence\Base\SpyApiKeyQuery;
use Orm\Zed\ApiKey\Persistence\SpyApiKey;
use Spryker\Zed\ApiKey\Persistence\Propel\Mapper\ApiKeyMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ApiKey\Persistence\ApiKeyRepositoryInterface getRepository()
 * @method \Spryker\Zed\ApiKey\Persistence\ApiKeyEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ApiKey\ApiKeyConfig getConfig()
 */
class ApiKeyPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ApiKey\Persistence\Base\SpyApiKeyQuery
     */
    public function createApiKeyQuery(): SpyApiKeyQuery
    {
        return SpyApiKeyQuery::create();
    }

    /**
     * @return \Orm\Zed\ApiKey\Persistence\SpyApiKey
     */
    public function createApiKeyEntity(): SpyApiKey
    {
        return new SpyApiKey();
    }

    /**
     * @return \Spryker\Zed\ApiKey\Persistence\Propel\Mapper\ApiKeyMapper
     */
    public function createApiKeyMapper(): ApiKeyMapper
    {
        return new ApiKeyMapper();
    }
}
