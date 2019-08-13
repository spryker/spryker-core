<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business;

use Codeception\Test\Unit;
use Elastica\Snapshot;
use Spryker\Client\Search\Provider\SearchClientProvider;
use Spryker\Zed\Search\Business\Model\Elasticsearch\SnapshotHandler;
use Spryker\Zed\Search\Business\SearchBusinessFactory;
use Spryker\Zed\Search\Business\SearchFacadeInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Facade
 * @group SearchFacadeTest
 * Add your own group annotations below this line
 */
class SearchFacadeTest extends Unit
{
    protected const REPOSITORY_LOCATION_FILE_NAME = 'search_test_file';
    protected const REPOSITORY_NAME = 'search_test_repository';

    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected $tester;

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
     * @return void
     */
    protected function skipIfCi(): void
    {
        if (getenv('CIRCLECI') || getenv('TRAVIS')) {
            $this->markTestSkipped('CircleCi/Travis not set up properly');
        }
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Search\Business\SearchBusinessFactory
     */
    protected function createSearchFactoryMock()
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
    protected function createSnapshotHandlerMock()
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
}
