<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Session\Business\Handler;

use Codeception\Test\Unit;
use PDO;
use ReflectionClass;
use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\Session\Business\Handler\SessionHandlerMysql;
use Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceBridge;
use Spryker\Zed\Propel\PropelConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Session
 * @group Business
 * @group Handler
 * @group SessionHandlerMysqlTest
 * Add your own group annotations below this line
 */
class SessionHandlerMysqlTest extends Unit
{
    protected const SESSION_ID = 'SESSION_ID';
    protected const SESSION_DATA = 'SESSION_DATA';

    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * @var bool
     */
    protected $shouldDropSessionTable;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (Config::get(PropelConstants::ZED_DB_ENGINE) !== PropelConfig::DB_ENGINE_MYSQL) {
            $this->markTestSkipped('MySQL related test');
        }

        $this->connection = new PDO(
            Config::get(PropelConstants::ZED_DB_ENGINE)
            . ':host='
            . Config::get(PropelConstants::ZED_DB_HOST)
            . ';port=' . Config::get(PropelConstants::ZED_DB_PORT)
            . ';dbname=' . Config::get(PropelConstants::ZED_DB_DATABASE),
            Config::get(PropelConstants::ZED_DB_USERNAME),
            Config::get(PropelConstants::ZED_DB_PASSWORD)
        );

        $this->shouldDropSessionTable = !$this->isSessionTableExists();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->shouldDropSessionTable) {
            $this->dropSessionTable();
        }
    }

    /**
     * @return void
     */
    public function testWriteShouldReturnFalseWhenNoDataPass(): void
    {
        // Arrange
        $sessionHandlerMySql = $this->createSessionHandlerMySql();

        // Act
        $result = $sessionHandlerMySql->write(static::SESSION_ID, '');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testWriteShouldReturnTrueWhenWriteData(): void
    {
        // Arrange
        $sessionHandlerMySql = $this->createSessionHandlerMySql();

        // Act
        $result = $sessionHandlerMySql->write(static::SESSION_ID, static::SESSION_DATA);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testReadShouldReturnEmptyStringWhenNoDataForSession(): void
    {
        // Arrange
        $sessionHandlerMySql = $this->createSessionHandlerMySql();

        // Act
        $result = $sessionHandlerMySql->read(null);

        // Assert
        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testReadShouldReturnContentOfSession(): void
    {
        // Arrange
        $sessionHandlerMySql = $this->createSessionHandlerMySql();
        $sessionHandlerMySql->write(static::SESSION_ID, static::SESSION_DATA);

        // Act
        $result = $sessionHandlerMySql->read(static::SESSION_ID);

        // Assert
        $this->assertSame(static::SESSION_DATA, $result);
    }

    /**
     * @return void
     */
    public function testDestroyShouldRemoveDataForSession(): void
    {
        // Arrange
        $sessionHandlerMySql = $this->createSessionHandlerMySql();
        $sessionHandlerMySql->write(static::SESSION_ID, static::SESSION_DATA);

        // Act
        $sessionHandlerMySql->destroy(static::SESSION_ID);

        // Assert
        $this->assertEmpty($sessionHandlerMySql->read(static::SESSION_ID));
    }

    /**
     * @return \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceBridge
     */
    protected function createMonitoringServiceMock(): SessionToMonitoringServiceBridge
    {
        $monitoringServiceInterfaceMock = $this->getMockBuilder(MonitoringServiceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        return new SessionToMonitoringServiceBridge($monitoringServiceInterfaceMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Session\Business\Handler\SessionHandlerMysql
     */
    protected function createSessionHandlerMySql(): SessionHandlerMysql
    {
        $sessionHandlerMySqlMock = $this->getMockBuilder(SessionHandlerMysql::class)
            ->onlyMethods(['getEnvironmentName'])
            ->disableOriginalConstructor()
            ->getMock();

        $sessionHandlerMySqlMock->method('getEnvironmentName')->willReturn('TESTING');

        $reflectionClass = new ReflectionClass($sessionHandlerMySqlMock);
        $properties = [
            'connection' => $this->connection,
            'monitoringService' => $this->createMonitoringServiceMock(),
            'lifetime' => 600,
        ];

        foreach ($properties as $propertyName => $propertyValue) {
            $property = $reflectionClass->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($sessionHandlerMySqlMock, $propertyValue);
            $property->setAccessible(false);
        }

        $method = $reflectionClass->getMethod('initDb');
        $method->setAccessible(true);
        $method->invoke($sessionHandlerMySqlMock);
        $method->setAccessible(false);

        return $sessionHandlerMySqlMock;
    }

    /**
     * @return bool
     */
    protected function isSessionTableExists(): bool
    {
        $searchSessionTableQuery = sprintf(
            "SELECT * FROM information_schema.tables WHERE table_schema = '%s' AND table_name = 'session' LIMIT 1;",
            Config::get(PropelConstants::ZED_DB_DATABASE)
        );
        $statement = $this->connection->query($searchSessionTableQuery);
        $statement->execute();

        return (bool)$statement->fetch();
    }

    /**
     * @return void
     */
    protected function dropSessionTable(): void
    {
        $dropSessionTableQuery = 'DROP TABLE IF EXISTS session';
        $statement = $this->connection->query($dropSessionTableQuery);
        $statement->execute();
    }
}
