<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Snapshot;

use Codeception\Test\Unit;
use Elastica\Snapshot as ElasticaSnapshot;
use RuntimeException;
use Spryker\Zed\SearchElasticsearch\Business\Snapshot\Snapshot;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Snapshot
 * @group SnapshotTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class SnapshotTest extends Unit
{
    protected const REPOSITORY_NAME = 'repository';
    protected const SNAPSHOT_NAME = 'snapshot';
    protected const OPTIONS = ['options'];

    /**
     * @var \Elastica\Snapshot|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $elasticaSnapshotMock;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Snapshot\Snapshot
     */
    protected $elasticsearchSnapshot;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->elasticaSnapshotMock = $this->createElasticaSnapshotMock();
        $this->elasticsearchSnapshot = new Snapshot(
            $this->elasticaSnapshotMock
        );
    }

    /**
     * @return void
     */
    public function testCanCreateSnapshot(): void
    {
        $this->elasticaSnapshotMock
            ->expects($this->once())
            ->method('createSnapshot')
            ->with(
                static::REPOSITORY_NAME,
                static::SNAPSHOT_NAME,
                static::OPTIONS,
                true
            )
            ->willReturnSelf();

        $this->elasticsearchSnapshot->createSnapshot(static::REPOSITORY_NAME, static::SNAPSHOT_NAME, static::OPTIONS);
    }

    /**
     * @return void
     */
    public function testCanRestoreSnapshot(): void
    {
        $this->elasticaSnapshotMock
            ->expects($this->once())
            ->method('restoreSnapshot')
            ->with(
                static::REPOSITORY_NAME,
                static::SNAPSHOT_NAME,
                static::OPTIONS,
                true
            )
            ->willReturnSelf();

        $this->elasticsearchSnapshot->restoreSnapshot(static::REPOSITORY_NAME, static::SNAPSHOT_NAME, static::OPTIONS);
    }

    /**
     * @return void
     */
    public function testCanGetSnapshot(): void
    {
        // Arrange
        $this->elasticaSnapshotMock
            ->expects($this->once())
            ->method('getSnapshot')
            ->with(static::REPOSITORY_NAME, static::SNAPSHOT_NAME)
            ->willReturnSelf();

        // Act
        $result = $this->elasticsearchSnapshot->existsSnapshot(static::REPOSITORY_NAME, static::SNAPSHOT_NAME);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testHandlesExceptionThrownWhenAttemptingToGetSnapshot(): void
    {
        // Arrange
        $this->elasticaSnapshotMock
            ->method('getSnapshot')
            ->will($this->throwException(new RuntimeException()));

        // Act
        $result = $this->elasticsearchSnapshot->existsSnapshot(static::REPOSITORY_NAME, static::SNAPSHOT_NAME);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return \Elastica\Snapshot|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createElasticaSnapshotMock(): ElasticaSnapshot
    {
        $elasticaSnapshotMock = $this->getMockBuilder(ElasticaSnapshot::class)
            ->setConstructorArgs([$this->tester->getFactory()->getElasticsearchClient()])
            ->setMethods(['createSnapshot', 'restoreSnapshot', 'getSnapshot', 'deleteSnapshot', 'isOk'])
            ->getMock();
        $elasticaSnapshotMock->method('isOk')->willReturn(true);

        return $elasticaSnapshotMock;
    }
}
