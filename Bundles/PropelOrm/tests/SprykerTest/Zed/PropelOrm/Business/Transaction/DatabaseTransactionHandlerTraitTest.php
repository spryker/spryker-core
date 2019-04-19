<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelOrm\Business\Transaction;

use Codeception\Test\Unit;
use Exception;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerTest\Zed\PropelOrm\Stub\DatabaseTransactionHandlerTraitStub;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PropelOrm
 * @group Business
 * @group Transaction
 * @group DatabaseTransactionHandlerTraitTest
 * Add your own group annotations below this line
 */
class DatabaseTransactionHandlerTraitTest extends Unit
{
    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface|\PHPUnit\Framework\MockObject\MockObject
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
        $callback = function () {
        };

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
