<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Persistence;

use Orm\Zed\Refund\Persistence\SpyRefundQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Refund\RefundConfig getConfig()
 * @method \Spryker\Zed\Refund\Persistence\RefundQueryContainer getQueryContainer()
 */
class RefundPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Refund\Persistence\SpyRefundQuery
     */
    public function createRefundQuery()
    {
        return SpyRefundQuery::create();
    }
}
