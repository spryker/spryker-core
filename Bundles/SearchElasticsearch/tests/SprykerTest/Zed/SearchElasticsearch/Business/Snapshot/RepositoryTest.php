<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Snapshot;

use Codeception\Test\Unit;
use Elastica\Exception\NotFoundException;
use Elastica\Exception\ResponseException;
use Elastica\Request;
use Elastica\Response;
use Elastica\Snapshot as ElasticaSnapshot;
use Spryker\Zed\SearchElasticsearch\Business\Snapshot\Repository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Snapshot
 * @group RepositoryTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class RepositoryTest extends Unit
{
    protected const TYPE_FILESYSTEM = 'fs';
    protected const TYPE_OTHER = 'other';
    protected const SETTINGS_LOCATION = 'location';
    protected const REPOSITORY_NAME = 'repository';
    protected const LOCATION = 'dummy location';

    /**
     * @var \Elastica\Snapshot|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $elasticaSnapshotMock;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Snapshot\Repository
     */
    protected $elasticsearchRepository;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->elasticaSnapshotMock = $this->createElasticaSnapshotMock();
        $this->elasticsearchRepository = new Repository(
            $this->elasticaSnapshotMock
        );
    }

    /**
     * @dataProvider buildsSettingsWithLocationProvider
     *
     * @param string[] $expectedSettings
     * @param string $type
     * @param string[] $inputSettings
     *
     * @return void
     */
    public function testBuildsSettingsWithLocation(array $expectedSettings, string $type, array $inputSettings): void
    {
        $this->elasticaSnapshotMock
            ->expects($this->once())
            ->method('registerRepository')
            ->with(static::REPOSITORY_NAME, $type, $expectedSettings)
            ->willReturnSelf();

        $this->elasticsearchRepository->registerSnapshotRepository(static::REPOSITORY_NAME, $type, $inputSettings);
    }

    /**
     * @return void
     */
    public function testCanGetRepository(): void
    {
        $this->elasticaSnapshotMock
            ->expects($this->once())
            ->method('getRepository')
            ->with(static::REPOSITORY_NAME);

        $result = $this->elasticsearchRepository->existsSnapshotRepository(static::REPOSITORY_NAME);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testHandlesResponseExceptionThrownWhenAttemptingToGetRepository(): void
    {
        // Arrange
        $this->elasticaSnapshotMock
            ->method('getRepository')
            ->will($this->throwException(
                new ResponseException(
                    $this->createMock(Request::class),
                    $this->createMock(Response::class)
                )
            ));

        // Act
        $result = $this->elasticsearchRepository->existsSnapshotRepository(static::REPOSITORY_NAME);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testHandlesNotFoundExceptionThrownWhenAttemptingToGetRepository(): void
    {
        // Arrange
        $this->elasticaSnapshotMock
            ->method('getRepository')
            ->will($this->throwException(new NotFoundException()));

        // Act
        $result = $this->elasticsearchRepository->existsSnapshotRepository(static::REPOSITORY_NAME);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return array
     */
    public function buildsSettingsWithLocationProvider(): array
    {
        return [
            'type is file system with no location set' => [
                [
                    static::SETTINGS_LOCATION => static::REPOSITORY_NAME,
                ],
                static::TYPE_FILESYSTEM,
                [],
            ],
            'type is file system with location set' => [
                [
                    static::SETTINGS_LOCATION => static::LOCATION,
                ],
                static::TYPE_FILESYSTEM,
                [
                    static::SETTINGS_LOCATION => static::LOCATION,
                ],
            ],
            'type is not file system with no location settings' => [
                [],
                static::TYPE_OTHER,
                [],
            ],
            'type is not file system with location settings' => [
                [
                    static::SETTINGS_LOCATION => static::LOCATION,
                ],
                static::TYPE_OTHER,
                [
                    static::SETTINGS_LOCATION => static::LOCATION,
                ],
            ],

        ];
    }

    /**
     * @return \Elastica\Snapshot|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createElasticaSnapshotMock(): ElasticaSnapshot
    {
        $elasticaSnapshotMock = $this->getMockBuilder(ElasticaSnapshot::class)
            ->setConstructorArgs([$this->tester->getFactory()->getElasticsearchClient()])
            ->setMethods(['registerRepository', 'getRepository', 'isOk'])
            ->getMock();
        $elasticaSnapshotMock->method('isOk')->willReturn(true);

        return $elasticaSnapshotMock;
    }
}
