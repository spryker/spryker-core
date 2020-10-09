<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilDataReader\Model\BatchIterator;

use Codeception\Test\Unit;
use Everon\Component\CriteriaBuilder\SqlPart;
use Spryker\Service\UtilDataReader\Model\BatchIterator\PdoBatchIterator;
use Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilder;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilDataReader
 * @group Model
 * @group BatchIterator
 * @group PdoBatchIteratorTest
 * Add your own group annotations below this line
 */
class PdoBatchIteratorTest extends Unit
{
    /**
     * @var \Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterfaces
     */
    protected $queryContainer;

    protected const TESTING_TABLE_NAME = 'foo';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->queryContainer = $this->getMockForAbstractClass(AbstractQueryContainer::class);
        $this->createTestingTable();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->dropTestingTable();
    }

    /**
     * @return void
     */
    public function testCountShouldReturnNumbersOfRowsInBatch(): void
    {
        // Arrange
        $pdoBatchIterator = $this->createPdoBatchIterator();
        $expectedCount = 3;
        $this->addRowsToTestingTable($expectedCount);

        // Act
        $count = $pdoBatchIterator->count();

        // Assert
        $this->assertSame($expectedCount, (int)$count);
    }

    /**
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\PdoBatchIterator
     */
    protected function createPdoBatchIterator(): PdoBatchIterator
    {
        $sqlPartMock = $this->getMockBuilder(SqlPart::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getSql'])
            ->getMock();

        $tableName = static::TESTING_TABLE_NAME;
        $selectTestingQuery = "SELECT * FROM $tableName";
        $sqlPartMock->expects($this->once())
            ->method('getSql')
            ->willReturn($selectTestingQuery);

        $criteriaBuilderMock = $this->getMockBuilder(CriteriaBuilder::class)
            ->onlyMethods(['toSqlPart'])
            ->getMock();
        $criteriaBuilderMock->expects($this->once())
            ->method('toSqlPart')
            ->willReturn($sqlPartMock);

        return new PdoBatchIterator(
            $criteriaBuilderMock,
            $this->queryContainer
        );
    }

    /**
     * @return void
     */
    protected function createTestingTable(): void
    {
        $tableName = static::TESTING_TABLE_NAME;
        $createTestingTableQuery = "CREATE TABLE IF NOT EXISTS $tableName (name varchar(20) NOT NULL);";

        $this->queryContainer->getConnection()->exec($createTestingTableQuery);
    }

    /**
     * @param int $numberOfRows
     *
     * @return void
     */
    protected function addRowsToTestingTable(int $numberOfRows): void
    {
        $tableName = static::TESTING_TABLE_NAME;
        $addRowQuery = "INSERT INTO $tableName (name) VALUES (?)";
        for ($i = 0; $i < $numberOfRows; $i++) {
            $statement = $this->queryContainer->getConnection()->prepare($addRowQuery);
            $statement->execute([$i]);
        }
    }

    /**
     * @return void
     */
    protected function dropTestingTable(): void
    {
        $tableName = static::TESTING_TABLE_NAME;
        $dropTestingTableQuery = "DROP TABLE IF EXISTS $tableName;";

        $this->queryContainer->getConnection()->exec($dropTestingTableQuery);
    }
}
