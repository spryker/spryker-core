<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Transaction;

use Propel\Runtime\Connection\ConnectionInterface;
use \Closure;

trait DatabaseTransactionHandlerTrait
{

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     * @param \Closure $callback
     *
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    protected function handleDatabaseTransaction(ConnectionInterface $connection, Closure $callback)
    {
        $connection->beginTransaction();

        try {
            $callback();

            $connection->commit();
        } catch (\Exception $exception) {
            $connection->rollBack();
            throw $exception;
        } catch (\Throwable $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

}
