<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StorageDatabase\Connection;

use Codeception\Test\Unit;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Client\StorageDatabase\StorageDatabaseFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group StorageDatabase
 * @group Connection
 * @group ConnectionProviderTest
 * Add your own group annotations below this line
 */
class ConnectionProviderTest extends Unit
{
    /**
     * @var \Spryker\Client\StorageDatabase\Connection\ConnectionProviderInterface
     */
    protected $connectionProvider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setupConnectionProvider();
    }

    /**
     * @return void
     */
    public function testProvidesConnection(): void
    {
        $connection = $this->connectionProvider->getConnection();

        $this->assertInstanceOf(ConnectionInterface::class, $connection);
    }

    /**
     * @return void
     */
    public function testProvidesTheSameConnectionInstanceOnSubsequentCalls(): void
    {
        $connection = $this->connectionProvider->getConnection();
        $anotherConnection = $this->connectionProvider->getConnection();

        $this->assertSame($connection, $anotherConnection);
    }

    /**
     * @return void
     */
    protected function setupConnectionProvider(): void
    {
        $this->connectionProvider = (new StorageDatabaseFactory())->createConnectionProvider();
    }
}
