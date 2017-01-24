<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Propel\Stub;

use Closure;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Propel\Business\Transaction\DatabaseTransactionHandlerTrait;

class DatabaseTransactionHandlerTraitStub
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param \Closure $callback
     *
     * @return void
     */
    public function execute(Closure $callback)
    {
        $this->handleDatabaseTransaction($this->connection, $callback);
    }

}
