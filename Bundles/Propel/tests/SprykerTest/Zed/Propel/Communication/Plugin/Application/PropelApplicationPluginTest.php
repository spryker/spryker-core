<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Communication\Plugin\Application;

use Codeception\Test\Unit;
use Propel\Runtime\Propel;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Communication
 * @group Plugin
 * @group Application
 * @group PropelApplicationPluginTest
 * Add your own group annotations below this line
 */
class PropelApplicationPluginTest extends Unit
{
    protected const DEFAULT_DATA_SOURCE_NAME = 'zed';

    /**
     * @var \SprykerTest\Zed\Propel\PropelCommunicationTester
     */
    protected $tester;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $readConnection;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $writeConnection;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->readConnection = Propel::getReadConnection(static::DEFAULT_DATA_SOURCE_NAME);
        $this->writeConnection = Propel::getWriteConnection(static::DEFAULT_DATA_SOURCE_NAME);
    }

    /**
     * @return void
     */
    public function testPropelHasEstablishedReadAndWriteConnections(): void
    {
        $this->assertSame(static::DEFAULT_DATA_SOURCE_NAME, $this->readConnection->getName());
        $this->assertSame(static::DEFAULT_DATA_SOURCE_NAME, $this->writeConnection->getName());
    }

    /**
     * @return void
     */
    public function testPropelConnectionManager(): void
    {
        $createTableQuery = 'CREATE TABLE IF NOT EXISTS spy_test_propel (id_test_propel INT NOT NULL, PRIMARY KEY (id_test_propel));';
        $createTableQueryResult = $this->writeConnection->prepare($createTableQuery)->execute();
        $this->assertTrue($createTableQueryResult);

        $insertQuery = sprintf('INSERT INTO spy_test_propel (id_test_propel) VALUES (%s);', mt_rand());
        $createQueryResult = $this->writeConnection->prepare($insertQuery)->execute();
        $this->assertTrue($createQueryResult);

        $selectQuery = 'SELECT * FROM spy_test_propel;';
        $selectQueryResult = $this->readConnection->prepare($selectQuery)->execute();
        $this->assertTrue($selectQueryResult);

        $deleteQuery = 'DELETE FROM spy_test_propel where id_test_propel IS NOT NULL;';
        $deleteQueryResult = $this->writeConnection->prepare($deleteQuery)->execute();
        $this->assertTrue($deleteQueryResult);

        $dropTableQuery = 'DROP TABLE IF EXISTS spy_test_propel;';
        $result = $this->writeConnection->prepare($dropTableQuery)->execute();
        $this->assertTrue($result);
    }
}
