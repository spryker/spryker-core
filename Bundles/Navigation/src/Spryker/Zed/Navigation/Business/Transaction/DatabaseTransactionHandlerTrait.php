<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Transaction;

use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Propel;
use \Closure;

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
    protected function handleDatabaseTransaction(Closure $callback, ConnectionInterface $connection = null)
    {
        if (!$connection) {
            $connection = Propel::getConnection();
        }

        $connection->beginTransaction();
        try {
            $result = $callback();
            $connection->commit();

            return $result;
        } catch (\Exception $exception) {
            $connection->rollBack();
            throw $exception;
        } catch (\Throwable $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

}
