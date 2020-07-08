<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business;

use Codeception\Test\Unit;
use Elastica\Snapshot;
use Psr\Log\NullLogger;
use Spryker\Client\Search\Provider\SearchClientProvider;
use Spryker\Zed\Search\Business\Model\Elasticsearch\SnapshotHandler;
use Spryker\Zed\Search\Business\Model\Elasticsearch\SnapshotHandlerInterface;
use Spryker\Zed\Search\Business\SearchBusinessFactory;
use Spryker\Zed\Search\Business\SearchFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Facade
 * @group SearchFacadeBCTest
 * Add your own group annotations below this line
 *
 * @deprecated Only for BC check.
 */
class SearchFacadeBCTest extends Unit
{
    protected const INDEX_NAME = 'de_search_devtest';
    protected const REPOSITORY_LOCATION_FILE_NAME = 'search_test_file';
    protected const REPOSITORY_NAME = 'search_test_repository';

    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();
    }

    /**
     * @return void
     */
    public function testCreateSnapshotRepository(): void
    {
        $this->skipIfCi();

        //Arrange
        $searchFactory = $this->createSearchFactoryMock();
        $searchFacade = $this->getSearchFacade($searchFactory);

        //Act
        $result = $searchFacade->createSnapshotRepository(static::REPOSITORY_NAME);

        //Assert
        $this->assertTrue($result);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Search\Business\SearchBusinessFactory
     */
    protected function createSearchFactoryMock(): SearchBusinessFactory
    {
        $searchFactoryMockBuilder = $this->getMockBuilder(SearchBusinessFactory::class)
            ->setMethods(['createSnapshotHandler']);

        $searchFactoryMock = $searchFactoryMockBuilder->getMock();

        $searchFactoryMock->method('createSnapshotHandler')->willReturn($this->createSnapshotHandlerMock());

        return $searchFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Search\Business\Model\Elasticsearch\SnapshotHandlerInterface
     */
    protected function createSnapshotHandlerMock(): SnapshotHandlerInterface
    {
        $snapshotHandlerMockBuilder = $this->getMockBuilder(SnapshotHandler::class)
            ->setConstructorArgs([$this->createElasticsearchSnapshot()])
            ->setMethods(['buildRepositorySettings']);

        $snapshotHandlerMock = $snapshotHandlerMockBuilder->getMock();

        $snapshotHandlerMock->method('buildRepositorySettings')->willReturn(['location' => $this->getVirtualRepositoryLocation()]);

        return $snapshotHandlerMock;
    }

    /**
     * @return \Elastica\Snapshot
     */
    protected function createElasticsearchSnapshot(): Snapshot
    {
        /** @var \Elastica\Client $searchClient */
        $searchClient = (new SearchClientProvider())->getInstance();

        return new Snapshot($searchClient);
    }

    /**
     * @return string
     */
    protected function getVirtualRepositoryLocation(): string
    {
        return $this->tester->getVirtualDirectory() . static::REPOSITORY_LOCATION_FILE_NAME;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Search\Business\SearchBusinessFactory|null $factory
     *
     * @return \Spryker\Zed\Search\Business\SearchFacadeInterface
     */
    protected function getSearchFacade($factory = null): SearchFacadeInterface
    {
        /** @var \Spryker\Zed\Search\Business\SearchFacade $searchFacade */
        $searchFacade = $this->tester->getFacade();

        if ($factory) {
            $searchFacade->setFactory($factory);
        }

        return $searchFacade;
    }

    /**
     * @return void
     */
    public function testDeleteDeletesAnIndex(): void
    {
        // Arrange
        $index = $this->tester->haveIndex(static::INDEX_NAME);

        // Act
        $response = $this->tester->getFacade()->delete();

        // Assert
        $this->assertTrue($response->isOk(), 'Delete response was expected to be true but is false.');
        $this->assertFalse($index->exists(), 'Index was expected to be deleted but still exists.');
    }

    /**
     * @return void
     */
    public function testGetTotalCountReturnsNumberOfDocumentsInAnIndex(): void
    {
        $this->skipIfCi();

        // Arrange
        $this->tester->haveDocumentInIndex(static::INDEX_NAME);

        // Act
        $response = $this->tester->getFacade()->getTotalCount();

        // Assert
        $this->assertSame(1, $response, sprintf('Expected exactly one document but found "%s".', $response));
    }

    /**
     * @return void
     */
    public function testInstallIndexInstallsIndices(): void
    {
        $this->skipIfCi();

        // Arrange
        $this->tester->mockConfigMethod('getClassTargetDirectory', codecept_output_dir());
        $this->tester->mockConfigMethod('getJsonIndexDefinitionDirectories', [
            codecept_data_dir('Fixtures/Definition/FinderBC'),
        ]);

        $logger = new NullLogger();

        // Act
        $this->tester->getFacade()->install($logger);

        // Assert
        $client = $this->tester->getFactory()->getElasticsearchClient();
        $index = $client->getIndex(static::INDEX_NAME);

        $this->assertTrue($index->exists(), 'Index was expected to be installed but was not.');

        $this->tester->getFacade()->delete(static::INDEX_NAME);
    }

    /**
     * @return void
     */
    protected function skipIfCi(): void
    {
        if (getenv('TRAVIS')) {
            $this->markTestSkipped('Travis not set up properly');
        }
    }
}
