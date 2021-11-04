<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclEntity\Persistence\Propel\QueryManager;

use Codeception\Test\Unit;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\AclEntity\Persistence\Exception\QueryMergerJoinMalfunctionException;
use Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMerger;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclEntity
 * @group Persistence
 * @group Propel
 * @group QueryManager
 * @group AclEntityQueryMergerTest
 * Add your own group annotations below this line
 */
class AclEntityQueryMergerTest extends Unit
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface
     */
    protected $merger;

    /**
     * @var \Propel\Runtime\ActiveQuery\ModelCriteria|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $modelCriteriaSrcMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->merger = new AclEntityQueryMerger();

        $this->modelCriteriaSrcMock = $this->createMock(ModelCriteria::class);
        $joinSrc1Mock = $this->getMockBuilder(Join::class)
            ->onlyMethods(['getRightTableName', 'getRightColumn', 'getLeftColumn', 'getJoinType'])
            ->getMock();
        $joinSrc1Mock->method('getRightTableName')->willReturn('Table1');
        $joinSrc1Mock->method('getRightColumn')->willReturn('table1.rightColumn');
        $joinSrc1Mock->method('getLeftColumn')->willReturn('leftColumn');
        $joinSrc1Mock->method('getJoinType')->willReturn(Join::INNER_JOIN);

        $joinSrc2Mock = $this->getMockBuilder(Join::class)
            ->onlyMethods(['getRightTableName', 'getRightColumn', 'getLeftColumn', 'getJoinType'])
            ->getMock();
        $joinSrc2Mock->method('getRightTableName')->willReturn('Table2');
        $joinSrc2Mock->method('getRightColumn')->willReturn('table2.rightColumn');
        $joinSrc2Mock->method('getLeftColumn')->willReturn('leftColumn');
        $joinSrc1Mock->method('getJoinType')->willReturn(Join::INNER_JOIN);

        $this->modelCriteriaSrcMock->method('getJoins')->willReturn([$joinSrc1Mock, $joinSrc2Mock]);
        $tableMap = $this->createMock(TableMap::class);
        $tableMap->method('getName')->willReturn('TableSrc');
        $this->modelCriteriaSrcMock->method('getTableMap')->willReturn($tableMap);
    }

    /**
     * @return void
     */
    public function testMergeQueriesWithoutJoins(): void
    {
        // Arrange
        $modelCriteriaDst = $this->createMock(ModelCriteria::class);
        $modelCriteriaDst->method('getJoins')->willReturn([]);

        // Assert
        $this->modelCriteriaSrcMock->expects($this->never())->method('getTableMap');

        // Act
        $this->merger->mergeQueries($this->modelCriteriaSrcMock, $modelCriteriaDst);
    }

    /**
     * @return void
     */
    public function testMergeQueriesWithAddingNewJoin(): void
    {
        // Arrange
        $modelCriteriaDst = $this->createMock(ModelCriteria::class);
        $joinDstMock = $this->createMock(Join::class);
        $joinDstMock->method('getRightTableName')->willReturn('Table3');
        $joinDstMock->method('getRightTableAliasOrName')->willReturn('Table3');
        $joinDstMock->method('getRightColumn')->willReturn('table3.rightColumn');
        $joinDstMock->method('getLeftColumn')->willReturn('leftColumn');
        $joinDstMock->method('getJoinType')->willReturn(Join::INNER_JOIN);
        $joinAliasDst = 'joinAliasDst';

        // Assert
        $modelCriteriaDst->method('getJoins')->willReturn([$joinAliasDst => $joinDstMock]);
        $this->modelCriteriaSrcMock
            ->expects($this->once())
            ->method('addJoinObject')
            ->with($joinDstMock, $joinAliasDst);

        // Act
        $this->merger->mergeQueries($this->modelCriteriaSrcMock, $modelCriteriaDst);
    }

    /**
     * @return void
     */
    public function testMergeQueriesWithMergeDuplicatedJoins(): void
    {
        // Arrange
        $modelCriteriaDstMock = $this->createMock(ModelCriteria::class);
        $joinDstMock = $this->getMockBuilder(Join::class)
            ->onlyMethods(
                ['getRightTableName', 'getRightTableAliasOrName', 'getRightColumn', 'getLeftColumn', 'getJoinType'],
            )
            ->getMock();
        $joinDstMock->method('getRightTableName')->willReturn('Table1');
        $joinDstMock->method('getRightTableAliasOrName')->willReturn('Table1');
        $joinDstMock->method('getRightColumn')->willReturn('table1.rightColumn');
        $joinDstMock->method('getLeftColumn')->willReturn('leftColumn');
        $joinDstMock->method('getJoinType')->willReturn(Join::INNER_JOIN);
        $joinAliasDst = 'joinAliasDst';

        // Assert
        $modelCriteriaDstMock->method('getJoins')->willReturn([$joinAliasDst => $joinDstMock]);
        $this->modelCriteriaSrcMock->expects($this->never())->method('addJoinObject');
        $this->modelCriteriaSrcMock->method('getAliases')->willReturn([$joinAliasDst]);

        // Act
        $this->merger->mergeQueries($this->modelCriteriaSrcMock, $modelCriteriaDstMock);
    }

    /**
     * @return void
     */
    public function testMergeQueriesWithoutMergeDuplicatedJoins(): void
    {
        // Arrange
        $joinAliasDst = 'joinAliasDst';
        $modelCriteriaDst = $this->createMock(ModelCriteria::class);
        $joinDstMock = $this->createMock(Join::class);
        $joinDstMock->method('getRightTableName')->willReturn('Table1');
        $joinDstMock->method('getRightColumn')->willReturn('table1.rightColumn');
        $joinDstMock->method('getLeftColumn')->willReturn('leftColumn');
        $joinDstMock->method('getJoinType')->willReturn('LEFT JOIN');
        $joinDstMock->method('getRightTableAliasOrName')->willReturn($joinAliasDst);
        $this->modelCriteriaSrcMock->method('getAliases')->willReturn(['RightTableAliasOrName']);
        $newJoinAliasDst = $joinAliasDst . '1';

        // Assert
        $joinDstMock->expects($this->once())->method('setRightTableAlias')->with($newJoinAliasDst);
        $modelCriteriaDst->method('getJoins')->willReturn([$joinAliasDst => $joinDstMock]);
        $this->modelCriteriaSrcMock
            ->expects($this->once())
            ->method('addJoinObject')
            ->with($joinDstMock, $newJoinAliasDst);

        // Act
        $this->merger->mergeQueries($this->modelCriteriaSrcMock, $modelCriteriaDst);
    }

    /**
     * @return void
     */
    public function testMergeQueryMalfunctedJoinException(): void
    {
        // Arrange
        $modelCriteriaDst = $this->createMock(ModelCriteria::class);
        $joinDstMock = $this->createMock(Join::class);
        $joinDstMock->method('getRightTableName')->willReturn(null);
        $joinDstMock->method('getRightColumn')->willReturn('table1.rightColumn');
        $joinDstMock->method('getLeftColumn')->willReturn('leftColumn');
        $joinDstMock->method('getJoinType')->willReturn('LEFT JOIN');
        $joinDstMock->method('getRightTableAliasOrName')->willReturn(null);
        $modelCriteriaDst->method('getJoins')->willReturn([$joinDstMock]);

        $this->expectException(QueryMergerJoinMalfunctionException::class);

        // Act
        $this->merger->mergeQueries($this->modelCriteriaSrcMock, $modelCriteriaDst);
    }
}
