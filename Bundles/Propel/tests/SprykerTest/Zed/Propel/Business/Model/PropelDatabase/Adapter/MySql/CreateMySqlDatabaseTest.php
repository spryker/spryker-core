<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql;

use Codeception\Test\Unit;
use Propel\Runtime\Propel;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\CreateMySqlDatabase;
use Spryker\Zed\Propel\PropelConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group PropelDatabase
 * @group Adapter
 * @group MySql
 * @group CreateMySqlDatabaseTest
 * Add your own group annotations below this line
 */
class CreateMySqlDatabaseTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Propel\PropelBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateIfNotExistsShouldCreateDatabase(): void
    {
        if (Config::get(PropelConstants::ZED_DB_ENGINE) !== PropelConfig::DB_ENGINE_MYSQL) {
            $this->markTestSkipped('MySQL related test');
        }

        // Arrange
        $mySqlDatabaseCreatorMock = new CreateMySqlDatabase();

        // Act
        $mySqlDatabaseCreatorMock->createIfNotExists();

        // Assert
        $this->assertNotEmpty($this->findCreatedDatabase());
    }

    /**
     * @return mixed
     */
    protected function findCreatedDatabase()
    {
        $databaseName = Config::get(PropelConstants::ZED_DB_DATABASE);
        $searchDbQuery = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$databaseName';";

        $connection = Propel::getConnection();
        $statement = $connection->prepare($searchDbQuery);
        $statement->execute();

        return $statement->fetch();
    }
}
