<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\PropelOrm\Business\Transaction;

use Exception;
use PHPUnit_Framework_TestCase;
use PropelOrm\Stub\DatabaseTransactionHandlerTraitStub;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group PropelOrm
 * @group Business
 * @group Transaction
 * @group DatabaseTransactionHandlerTraitTest
 */
class DatabaseTransactionHandlerTraitTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $connection;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->connection = $this->getMockBuilder(ConnectionInterface::class)->getMock();
    }

    /**
     * @return void
     */
    public function testShouldCommitWhenNoErrors()
    {
        $callback = function () {};

        $this->connection
            ->expects($this->once())
            ->method('beginTransaction');

        $this->connection
            ->expects($this->once())
            ->method('commit');

        $databaseTransactionHandlerTraitStub = new DatabaseTransactionHandlerTraitStub($this->connection);

        $databaseTransactionHandlerTraitStub->execute($callback);
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function testShouldCatchExceptionAndRollback()
    {
        $callback = function () {
            throw new Exception('Error when saving');
        };

        $this->connection
            ->expects($this->once())
            ->method('beginTransaction');

        $this->connection
            ->expects($this->once())
            ->method('rollBack');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error when saving');

        $databaseTransactionHandlerTraitStub = new DatabaseTransactionHandlerTraitStub($this->connection);

        $databaseTransactionHandlerTraitStub->execute($callback);
    }

}
