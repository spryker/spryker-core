<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Persistence;

use Orm\Zed\ManualOrderEntry\Persistence\SpyOrderSourceQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

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
}
