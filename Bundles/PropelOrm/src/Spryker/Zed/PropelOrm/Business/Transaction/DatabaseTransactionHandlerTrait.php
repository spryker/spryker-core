<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm\Business\Transaction;

use Closure;
use Exception;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Throwable;

/**
 * @deprecated use \Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait instead
 */
trait DatabaseTransactionHandlerTrait
{
    /**
     * @param \Closure $callback
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $connection
     *
     * @throws \Exception
     * @throws \Throwable
     *
     * @return mixed
     */
    protected function handleDatabaseTransaction(Closure $callback, ?ConnectionInterface $connection = null)
    {
        if (!$connection) {
            $connection = Propel::getConnection();
        }

        $connection->beginTransaction();

        try {
            $result = $callback();

            $connection->commit();

            return $result;
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        } catch (Throwable $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function preventTransaction()
    {
        if (Propel::getConnection()->inTransaction()) {
            throw new PropelException('This operation is not allowed inside of transaction');
        }
    }

    /**
     * @return void
     */
    protected function disableInstancePooling()
    {
        Propel::disableInstancePooling();
    }

    /**
     * @return void
     */
    protected function enableInstancePooling()
    {
        Propel::enableInstancePooling();
    }
}
