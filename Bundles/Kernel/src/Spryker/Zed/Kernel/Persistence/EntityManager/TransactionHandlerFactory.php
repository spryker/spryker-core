<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\EntityManager;

use Spryker\Zed\Kernel\Persistence\EntityManager\PropelDatabaseTransactionHandler;

class TransactionHandlerFactory
{
    /**
     * @return \Spryker\Zed\Kernel\Persistence\EntityManager\TransactionHandlerInterface
     */
    public static function createHandler()
    {
        //@todo should get from configuration.
        return new PropelDatabaseTransactionHandler();
    }
}
