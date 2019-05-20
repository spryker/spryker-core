<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SharedCartsRestApi\Persistence\Mapper\SharedCartsRestApiMapper;

/**
 * @method \Spryker\Zed\SharedCartsRestApi\Persistence\SharedCartsRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SharedCartsRestApi\SharedCartsRestApiConfig getConfig()
 */
class SharedCartsRestApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Persistence\Mapper\SharedCartsRestApiMapper
     */
    public function createSharedCartRestApiMapper(): SharedCartsRestApiMapper
    {
        return new SharedCartsRestApiMapper();
    }
}
