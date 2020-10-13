<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilDataReader\Model\BatchIterator;

use Codeception\Test\Unit;
use Everon\Component\CriteriaBuilder\SqlPart;
use Generated\Shared\Transfer\ColumnTransfer;
use Generated\Shared\Transfer\TableTransfer;
use Propel\Generator\Model\PropelTypes;
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
     * @var \SprykerTest\Service\UtilDataReader\UtilDataReaderServiceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    protected $queryContainer;

    /**
     * @var \Propel\Generator\Model\Table
     */
    protected $table;

    protected const TESTING_TABLE_NAME = 'foo';
    protected const TESTING_COLUMN_NAME = 'id_foo';
    protected const TESTING_COLUMN_TYPE = PropelTypes::INTEGER;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->queryContainer = $this->getMockForAbstractClass(AbstractQueryContainer::class);

        $this->table = $this->tester->createTable($this->buildTableTransfer());
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->dropTable($this->table);
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
     * @return \Generated\Shared\Transfer\TableTransfer
     */
    protected function buildTableTransfer(): TableTransfer
    {
        $columnTransfer = (new ColumnTransfer())->fromArray([
            'name' => static::TESTING_COLUMN_NAME,
            'type' => static::TESTING_COLUMN_TYPE,
        ]);

        return (new TableTransfer())
            ->setName(static::TESTING_TABLE_NAME)
            ->setNamespace(__NAMESPACE__)
            ->addColumns($columnTransfer);
    }

    /**
     * @param int $numberOfRows
     *
     * @return void
     */
    protected function addRowsToTestingTable(int $numberOfRows): void
    {
        $tableName = static::TESTING_TABLE_NAME;
        $tableColumns = static::TESTING_COLUMN_NAME;
        $addRowQuery = "INSERT INTO $tableName ($tableColumns) VALUES (?)";
        for ($i = 0; $i < $numberOfRows; $i++) {
            $statement = $this->queryContainer->getConnection()->prepare($addRowQuery);
            $statement->execute([$i]);
        }
    }
}
