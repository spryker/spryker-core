<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\EntityManager;

trait TransactionTrait
{
    /**
     * @return \Spryker\Zed\Kernel\Persistence\EntityManager\TransactionHandlerInterface
     */
    public function getTransactionHandler()
    {
        return $this->createTransactionHandlerFactory()->createHandler();
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\EntityManager\TransactionHandlerFactoryInterface
     */
    protected function createTransactionHandlerFactory()
    {
        return new TransactionHandlerFactory();
    }
}
