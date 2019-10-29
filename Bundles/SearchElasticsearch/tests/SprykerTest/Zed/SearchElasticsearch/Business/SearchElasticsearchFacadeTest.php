<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business;

use Codeception\Test\Unit;
use Elastica\Index;
use Generated\Shared\Transfer\StoreTransfer;
use Psr\Log\NullLogger;
use Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreClientInterface;
use Spryker\Zed\SearchElasticsearch\Business\Snapshot\Repository;
use Spryker\Zed\SearchElasticsearch\Business\Snapshot\RepositoryInterface;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Facade
 * @group SearchElasticsearchFacadeTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class SearchElasticsearchFacadeTest extends Unit
{
    protected const CURRENT_STORE = 'DE';
    protected const SOURCE_IDENTIFIER = 'index-name';
    protected const INDEX_NAME_ALL = '*_testing';
    protected const REPOSITORY_LOCATION_FILE_NAME = 'search_test_file';
    protected const REPOSITORY_NAME = 'search_test_repository';

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->mockConfigMethod('getIndexNameAll', static::INDEX_NAME_ALL);
    }

    /**
     * @return void
     */
    public function testInstallsIndices(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getJsonSchemaDefinitionDirectories', $this->tester->getFixturesSchemaDirectory());
        $this->setupStoreClientDependency();
        $expectedIndexName = $this->tester->translateSourceIdentifierToIndexName(static::SOURCE_IDENTIFIER);
        $this->tester->addCleanupForIndexByName($expectedIndexName);

        // Act
        $this->tester->getFacade()->install(new NullLogger());

        // Assert
        $this->tester->assertIndexExists($expectedIndexName);
    }

    /**
     * @return void
     */
    public function testGeneratesIndexMaps(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getJsonSchemaDefinitionDirectories', $this->tester->getFixturesSchemaDirectory());
        $this->tester->mockConfigMethod('getClassTargetDirectory', $this->tester->getFixturesIndexMapDirectory());

        // Act
        $this->tester->getFacade()->installMapper(new NullLogger());

        // Assert
        $this->tester->assertIndexMapGenerated(static::SOURCE_IDENTIFIER);
    }

    /**
     * @return void
     */
    public function testCanCloseIndex(): void
    {
        // Arrange
        $index = $this->tester->haveIndex('dummy_index_name');

        // Act
        $result = $this->tester->getFacade()->closeIndex(
            $this->tester->buildSearchContextTransferFromIndexName($index->getName())
        );

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(SearchElasticsearchConfig::INDEX_CLOSE_STATE, $this->getIndexState($index));
    }

    /**
     * @return void
     */
    public function testCanCloseAllIndices(): void
    {
        // Arrange
        /** @var \Elastica\Index $index */
        $index = $this->tester->haveIndex('dummy_index_name');
        $anotherIndex = $this->tester->haveIndex('another_dummy_index_name');

        // Act
        $result = $this->tester->getFacade()->closeIndices();

        // Assert
        $this->assertTrue($result);
        $this->assertAllIndicesAreOfExpectedState([$index, $anotherIndex], SearchElasticsearchConfig::INDEX_CLOSE_STATE);
    }

    /**
     * @return void
     */
    public function testCanOpenOneIndex(): void
    {
        // Arrange
        /** @var \Elastica\Index $index */
        $index = $this->tester->haveIndex('dummy_index_name');
        $searchContextTransfer = $this->tester->buildSearchContextTransferFromIndexName($index->getName());
        $this->tester->getFacade()->closeIndex($searchContextTransfer);

        // Act
        $result = $this->tester->getFacade()->openIndex($searchContextTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(SearchElasticsearchConfig::INDEX_OPEN_STATE, $this->getIndexState($index));
    }

    /**
     * @return void
     */
    public function testCanOpenAllIndices(): void
    {
        // Arrange
        /** @var \Elastica\Index $index */
        $index = $this->tester->haveIndex('dummy_index_name');
        $anotherIndex = $this->tester->haveIndex('another_dummy_index_name');
        $this->tester->getFacade()->closeIndices();

        // Act
        $result = $this->tester->getFacade()->openIndices();

        // Assert
        $this->assertTrue($result);
        $this->assertAllIndicesAreOfExpectedState([$index, $anotherIndex], SearchElasticsearchConfig::INDEX_OPEN_STATE);
    }

    /**
     * @return void
     */
    public function testCanDeleteIndex(): void
    {
        // Arrange
        $index = $this->tester->haveIndex('dummy_index_name');
        $searchContextTransfer = $this->tester->buildSearchContextTransferFromIndexName($index->getName());

        // Act
        $this->tester->getFacade()->deleteIndex($searchContextTransfer);

        // Assert
        $this->assertFalse($index->exists());
    }

    /**
     * @return void
     */
    public function testCanDeleteAllIndices(): void
    {
        // Arrange
        $index = $this->tester->haveIndex('dummy_index_name');
        $anotherIndex = $this->tester->haveIndex('another_dummy_index_name');

        // Act
        $this->tester->getFacade()->deleteIndices();

        // Assert
        $this->assertAllIndicesAreDeleted([$index, $anotherIndex]);
    }

    /**
     * @return void
     */
    protected function setupStoreClientDependency(): void
    {
        $this->tester->setDependency(
            SearchElasticsearchDependencyProvider::CLIENT_STORE,
            $this->getStoreClientMock()
        );
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getStoreClientMock(): SearchElasticsearchToStoreClientInterface
    {
        $mockStoreTransfer = new StoreTransfer();
        $mockStoreTransfer->setName(static::CURRENT_STORE);

        $storeMock = $this->createMock(SearchElasticsearchToStoreClientInterface::class);
        $storeMock->method('getCurrentStore')->willReturn($mockStoreTransfer);

        return $storeMock;
    }

    /**
     * @param \Elastica\Index[] $indices
     * @param string $expectedState
     *
     * @return void
     */
    protected function assertAllIndicesAreOfExpectedState(array $indices, string $expectedState): void
    {
        $allIndicesAreOpen = true;

        foreach ($indices as $index) {
            $indexState = $this->getIndexState($index);

            if ($indexState !== $expectedState) {
                $allIndicesAreOpen = false;

                break;
            }
        }

        $this->assertTrue($allIndicesAreOpen);
    }

    /**
     * @param \Elastica\Index[] $indices
     *
     * @return void
     */
    protected function assertAllIndicesAreDeleted(array $indices): void
    {
        $allIndicesAreDeleted = true;

        foreach ($indices as $index) {
            if ($index->exists()) {
                $allIndicesAreDeleted = false;

                break;
            }
        }

        $this->assertTrue($allIndicesAreDeleted);
    }

    /**
     * @param \Elastica\Index $index
     *
     * @return string
     */
    protected function getIndexState(Index $index): string
    {
        $clusterState = $index->getClient()->getCluster()->getState();

        if (isset($clusterState['metadata']['indices'][$index->getName()]['state'])) {
            return $clusterState['metadata']['indices'][$index->getName()]['state'];
        }

        return '';
    }

    /**
     * @return void
     */
    public function testCanCreateSnapshotRepository(): void
    {
        //Act
        $result = $this->tester->getFacade()->registerSnapshotRepository(static::REPOSITORY_NAME);

        //Assert
        $this->assertTrue($result);
        $this->assertRepositoryExists(static::REPOSITORY_NAME);
    }

    /**
     * @return void
     */
    public function testCanCheckForRepositoryExistence(): void
    {
        // Arrange
        $repositoryName = 'repository-name';
        $this->tester->haveRepository($repositoryName);

        // Act
        $result = $this->tester->getFacade()->existsSnapshotRepository($repositoryName);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SearchElasticsearch\Business\Snapshot\RepositoryInterface
     */
    protected function createRepositoryMock(): RepositoryInterface
    {
        $repositoryMock = $this->getMockBuilder(Repository::class)
            ->setConstructorArgs([$this->tester->getFactory()->createElasticaSnapshot()])
            ->setMethods(['buildRepositorySettings'])
            ->getMock();

        $repositoryMock->method('buildRepositorySettings')->willReturn(['location' => $this->tester->getVirtualRepositoryLocation()]);

        return $repositoryMock;
    }

    /**
     * @param string $repositoryName
     *
     * @return void
     */
    protected function assertRepositoryExists(string $repositoryName): void
    {
        $this->assertTrue(
            $this->createRepositoryMock()->existsSnapshotRepository($repositoryName)
        );
    }

    /**
     * @return void
     */
    public function testCanCreateSnapshot(): void
    {
        $repositoryName = 'repository-name';
        $snapshotName = 'snapshot-name';

        $this->tester->haveRepository($repositoryName);
        $this->tester->getFacade()->createSnapshot($repositoryName, $snapshotName);

        $result = $this->tester->getFactory()->createElasticaSnapshot()->getSnapshot($repositoryName, $snapshotName);
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanCheckForSnapshotExistence(): void
    {
        $repositoryName = 'repository-name';
        $snapshotName = 'snapshot-name';

        $this->tester->haveRepository($repositoryName);
        $this->tester->getFacade()->existsSnapshot($repositoryName, $snapshotName);

        $result = $this->tester->getFactory()->createElasticaSnapshot()->getSnapshot($repositoryName, $snapshotName);
        $this->assertTrue($result);
    }
}
