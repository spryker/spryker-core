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
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionHandlerInterface;
use Throwable;

class PropelDatabaseTransactionHandler implements TransactionHandlerInterface
{
    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @param \Closure $callback
     *
     * @throws \Exception
     * @throws \Throwable
     *
     * @return mixed
     */
    public function handleTransaction(Closure $callback)
    {
        if (!$this->connection) {
            $this->connection = Propel::getConnection();
        }

        $this->connection->beginTransaction();

        try {
            $result = $callback();

            $this->connection->commit();

            return $result;
        } catch (Exception $exception) {
            $this->connection->rollBack();
            throw $exception;
        } catch (Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return void
     */
    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;
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
