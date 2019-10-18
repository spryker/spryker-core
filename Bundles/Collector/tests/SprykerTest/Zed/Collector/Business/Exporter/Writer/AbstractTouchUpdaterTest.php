<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Collector\Business\Exporter\Writer;

use Codeception\Test\Unit;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet;
use Spryker\Zed\Collector\Business\Model\BulkTouchQueryBuilder;
use Spryker\Zed\Collector\CollectorConfig;
use SprykerTest\Zed\Collector\Business\Fixture\CollectorConfigWithNotDefinedDbEngineFake;
use SprykerTest\Zed\Collector\Business\Fixture\TouchUpdaterStub;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Collector
 * @group Business
 * @group Exporter
 * @group Writer
 * @group AbstractTouchUpdaterTest
 * Add your own group annotations below this line
 */
class AbstractTouchUpdaterTest extends Unit
{
    /**
     * @var \Spryker\Zed\Collector\CollectorConfig
     */
    protected $collectorConfig;

    /**
     * @var \Spryker\Zed\Collector\Persistence\Pdo\BulkUpdateTouchKeyByIdQueryInterface
     */
    protected $bulkTouchUpdateQuery;

    /**
     * @var \Spryker\Zed\Collector\Persistence\Pdo\BulkDeleteTouchByIdQueryInterface
     */
    protected $bulkTouchDeleteQuery;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->collectorConfig = $this->createCollectorConfig();
    }

    /**
     * @return void
     */
    public function testBulkUpdateBuildsAndExecutesQueries()
    {
        $touchUpdaterSet = $this->createTouchUpdaterSet();
        $idLocale = 1;
        $idStore = 1;
        $connection = $this->createConnectionMock();

        $touchUpdater = $this->createTouchUpdater();
        $databaseEngine = $this->collectorConfig->getCurrentEngineName();
        $expectedQuery = '';

        if ($databaseEngine === $this->collectorConfig->getPostgresEngineName()) {
            $expectedQuery =
                "UPDATE touchKeyTableName_value SET key = 'data_key1' WHERE touchKeyIdColumnName_value = 'new value'; \n"
                . "UPDATE touchKeyTableName_value SET key = 'data_key2' WHERE touchKeyIdColumnName_value = 'new value2'";
        }

        if ($databaseEngine === $this->collectorConfig->getMysqlEngineName()) {
            $expectedQuery =
                "UPDATE `touchKeyTableName_value` SET `key` = 'data_key1' WHERE `touchKeyTableName_value`.`touchKeyIdColumnName_value` = 'new value'; \n"
                . "UPDATE `touchKeyTableName_value` SET `key` = 'data_key2' WHERE `touchKeyTableName_value`.`touchKeyIdColumnName_value` = 'new value2'";
        }

        $connection->expects($this->once())
            ->method('exec')
            ->with($expectedQuery);

        $this->assertEmpty($this->bulkTouchUpdateQuery->getRawSqlString());

        $touchUpdater->bulkUpdate($touchUpdaterSet, $idLocale, $idStore, $connection);
    }

    /**
     * @return void
     */
    public function testBulkDeleteBuildsAndExecutesQueries()
    {
        $touchUpdaterSet = $this->createTouchUpdaterSet();
        $idLocale = 1;
        $connection = $this->createConnectionMock();

        $touchUpdater = $this->createTouchUpdater();
        $databaseEngine = $this->collectorConfig->getCurrentEngineName();
        $expectedQuery = '';

        if ($databaseEngine === $this->collectorConfig->getPostgresEngineName()) {
            $expectedQuery = 'DELETE FROM touchKeyTableName_value WHERE fk_touch IN (id_touch1,id_touch2)';
        }

        if ($databaseEngine === $this->collectorConfig->getMysqlEngineName()) {
            $expectedQuery = 'DELETE FROM `touchKeyTableName_value` WHERE `fk_touch` IN (id_touch1,id_touch2)';
        }

        $connection->expects($this->once())
            ->method('exec')
            ->with($expectedQuery);

        $this->assertEmpty($this->bulkTouchDeleteQuery->getRawSqlString());

        $touchUpdater->bulkDelete($touchUpdaterSet, $idLocale, $connection);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Can't resolve bulk touch class name: BulkUpdateTouchKeyByIdQuery
     *
     * @return void
     */
    public function testBulkUpdateIsFailingWithWrongTouchQueryConfigured()
    {
        $this->collectorConfig = $this->createWrongCollectorConfig();
        $this->createTouchUpdater();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Can't resolve bulk touch class name: BulkDeleteTouchByIdQuery
     *
     * @return void
     */
    public function testBulkDeleteIsFailingWithWrongTouchQueryConfigured()
    {
        $this->collectorConfig = $this->createWrongCollectorConfig();
        $this->createBulkTouchDeleteQuery();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerTest\Zed\Collector\Business\Fixture\TouchUpdaterStub
     */
    protected function createTouchUpdater()
    {
        return new TouchUpdaterStub($this->createBulkTouchUpdateQuery(), $this->createBulkTouchDeleteQuery());
    }

    /**
     * @return \Spryker\Zed\Collector\Persistence\Pdo\BulkUpdateTouchKeyByIdQueryInterface
     */
    protected function createBulkTouchUpdateQuery()
    {
        $this->bulkTouchUpdateQuery = $this->createBulkTouchQueryBuilder()
            ->createBulkTouchUpdateQuery();

        return $this->bulkTouchUpdateQuery;
    }

    /**
     * @return \Spryker\Zed\Collector\Persistence\Pdo\BulkDeleteTouchByIdQueryInterface
     */
    protected function createBulkTouchDeleteQuery()
    {
        $this->bulkTouchDeleteQuery = $this->createBulkTouchQueryBuilder()
            ->createBulkTouchDeleteQuery();

        return $this->bulkTouchDeleteQuery;
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Model\BulkTouchQueryBuilder
     */
    protected function createBulkTouchQueryBuilder()
    {
        return new BulkTouchQueryBuilder($this->collectorConfig);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createConnectionMock()
    {
        return $this->getMockForAbstractClass(ConnectionInterface::class, [], '', true, true, true, ['exec']);
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet
     */
    protected function createTouchUpdaterSet()
    {
        $touchUpdaterSet = new TouchUpdaterSet('collector_touch_id');
        $touchUpdaterSet->add(
            'data_key1',
            'id_touch1',
            [
                'collector_touch_id' => 'touch_id',
                'touchKeyColumnName_value' => 'new value',
            ]
        );

        $touchUpdaterSet->add(
            'data_key2',
            'id_touch2',
            [
                'collector_touch_id' => 'touch_id2',
                'touchKeyColumnName_value' => 'new value2',
            ]
        );

        return $touchUpdaterSet;
    }

    /**
     * @return \Spryker\Zed\Collector\CollectorConfig
     */
    protected function createCollectorConfig()
    {
        return new CollectorConfig();
    }

    /**
     * @return \Spryker\Zed\Collector\CollectorConfig
     */
    protected function createWrongCollectorConfig()
    {
        return new CollectorConfigWithNotDefinedDbEngineFake();
    }
}
