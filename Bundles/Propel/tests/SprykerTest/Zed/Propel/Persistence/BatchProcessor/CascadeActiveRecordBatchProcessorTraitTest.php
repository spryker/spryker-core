<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Persistence\BatchProcessor;

use Codeception\Test\Unit;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Zed\Propel\Persistence\BatchProcessor\CascadeActiveRecordBatchProcessorTrait;
use SprykerTest\Zed\Propel\TestCascadeProcessor;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Persistence
 * @group BatchProcessor
 * @group CascadeActiveRecordBatchProcessorTraitTest
 * Add your own group annotations below this line
 */
class CascadeActiveRecordBatchProcessorTraitTest extends Unit
{
    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Persistence\BatchProcessor\CascadeActiveRecordBatchProcessorTrait
     */
    protected function getCascadeActiveRecordBatchProcessor()
    {
        return $this->getMockForTrait(CascadeActiveRecordBatchProcessorTrait::class);
    }

    /**
     * @return \SprykerTest\Zed\Propel\TestCascadeProcessor
     */
    protected function getTraitTestClass(): TestCascadeProcessor
    {
        // To have a possibility to mock methods in trait
        return new TestCascadeProcessor();
    }

    /**
     * @return void
     */
    public function testSharedPersistAddsEntityToEntityList(): void
    {
        // Arrange
        $batchProcessor = $this->getTraitTestClass();

        $mockEntity = $this->createMock(ActiveRecordInterface::class);
        $mockEntity2 = $this->createMock(ActiveRecordInterface::class);

        // Act
        $batchProcessor->sharedPersist($mockEntity);
        $batchProcessor->sharedPersist($mockEntity2);

        // Assert
        $entityList = $batchProcessor->getEntityList();

        $this->assertCount(2, $entityList);
        $this->assertSame($mockEntity, $entityList[0]);
        $this->assertSame($mockEntity2, $entityList[1]);
    }

    /**
     * @return void
     */
    public function testRecursiveCommitWithEmptyEntityList(): void
    {
        // Arrange
        $batchProcessor = $this->getMockBuilder(TestCascadeProcessor::class)
            ->onlyMethods(['commit'])
            ->getMock();

        $batchProcessor->expects($this->never())
            ->method('commit');

        $result = $batchProcessor->recursiveCommit();

        // Assert
        $this->assertTrue($result);
        $this->assertEmpty($batchProcessor->getEntityList());
    }

    /**
     * @return void
     */
    public function testRecursiveCommitProcessesEntities(): void
    {
        // Arrange
        $batchProcessor = $this->getMockBuilder(get_class($this->getTraitTestClass()))
            ->onlyMethods(['persist', 'commit'])
            ->getMock();

        $mockEntity1 = $this->createMock(ActiveRecordInterface::class);
        $mockEntity2 = $this->createMock(ActiveRecordInterface::class);

        $persistCount = 0;
        $batchProcessor->expects($this->exactly(2))
            ->method('persist')
            ->willReturnCallback(function (ActiveRecordInterface $entity) use ($mockEntity1, $mockEntity2, &$persistCount) {
                if ($persistCount === 0) {
                    $this->identicalTo($mockEntity1);
                }
                if ($persistCount === 1) {
                    $this->identicalTo($mockEntity2);
                }
                $persistCount++;
            });

        $batchProcessor->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        // Act
        $batchProcessor->sharedPersist($mockEntity1);
        $batchProcessor->sharedPersist($mockEntity2);
        $result = $batchProcessor->recursiveCommit();

        // Assert
        $this->assertTrue($result);
        $this->assertEmpty($batchProcessor->getEntityList());
    }

    /**
     * @return void
     */
    public function testRecursiveCommitHandlesNestedEntities(): void
    {
        // Arrange
        $batchProcessor = $this->getMockBuilder(get_class($this->getTraitTestClass()))
            ->onlyMethods(['persist', 'commit'])
            ->getMock();

        $mockEntity1 = $this->createMock(ActiveRecordInterface::class);
        $mockEntity2 = $this->createMock(ActiveRecordInterface::class);
        $mockEntity3 = $this->createMock(ActiveRecordInterface::class);

        $batchProcessor->expects($this->exactly(3))
            ->method('persist');

        $persistCount = 0;
        $batchProcessor->expects($this->exactly(2))
            ->method('commit')
            ->willReturnCallback(function () use ($batchProcessor, $mockEntity3, &$persistCount) {
                if ($persistCount === 0) {
                    $batchProcessor->sharedPersist($mockEntity3);
                }
                $persistCount++;

                return true;
            });

        // Act
        $batchProcessor->sharedPersist($mockEntity1);
        $batchProcessor->sharedPersist($mockEntity2);
        $result = $batchProcessor->recursiveCommit();

        // Assert
        $this->assertTrue($result);
        $this->assertEmpty($batchProcessor->getEntityList());
    }
}
