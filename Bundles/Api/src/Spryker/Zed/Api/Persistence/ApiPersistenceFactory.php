<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Persistence;

use Spryker\Zed\Api\Persistence\Mapper\ApiCollectionMapper;
use Spryker\Zed\Api\Persistence\Mapper\ApiItemMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface getQueryContainer()
 */
class ApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\Api\Persistence\Mapper\ApiCollectionMapperInterface
     */
    public function createApiCollectionMapper()
    {
        return new ApiCollectionMapper();
    }

    /**
     * @return \Spryker\Zed\Api\Persistence\Mapper\ApiItemMapperInterface
     */
    public function createApiItemMapper()
    {
        return new ApiItemMapper();
    }
}
