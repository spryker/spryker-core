<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Persistence;

use Orm\Zed\ManualOrderEntry\Persistence\SpyOrderSourceQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ManualOrderEntry\Persistence\Propel\Mapper\OrderSourceMapper;
use Spryker\Zed\ManualOrderEntry\Persistence\Propel\Mapper\OrderSourceMapperInterface;

/**
 * @method \Spryker\Zed\ManualOrderEntry\ManualOrderEntryConfig getConfig()
 */
class ManualOrderEntryPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ManualOrderEntry\Persistence\SpyOrderSourceQuery
     */
    public function createOrderSourceQuery()
    {
        return SpyOrderSourceQuery::create();
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntry\Persistence\Propel\Mapper\OrderSourceMapperInterface
     */
    public function createOrderSourceMapper(): OrderSourceMapperInterface
    {
        return new OrderSourceMapper();
    }
}
