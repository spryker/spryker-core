<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business;

use Codeception\Test\Unit;
use Elastica\Index;
use Elastica\Request;
use Psr\Log\NullLogger;
use Spryker\Zed\SearchElasticsearch\Business\Snapshot\Repository;
use Spryker\Zed\SearchElasticsearch\Business\Snapshot\RepositoryInterface;
use Spryker\Zed\SearchElasticsearch\Business\SourceIdentifier\SourceIdentifierInterface;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig;
use SprykerTest\Shared\SearchElasticsearch\Helper\ElasticsearchHelper;

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
    protected const FIXTURE_SOURCE_IDENTIFIER = 'index-name';
    protected const DOCUMENT_CONTENT_KEY = '_source';
    protected const REPOSITORY_LOCATION_FILE_NAME = 'search_test_file';
    protected const REPOSITORY_NAME = 'search_test_repository';
    protected const REPOSITORY_TYPE_FILESYSTEM = 'fs';
    protected const SNAPSHOT_NAME = 'search_test_snapshot';

    /**
     * @return void
     */
    public function testInstallsIndexes(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getJsonSchemaDefinitionDirectories', $this->tester->getFixturesSchemaDirectory());
        $this->tester->mockConfigMethod('getSupportedSourceIdentifiers', [static::FIXTURE_SOURCE_IDENTIFIER]);
        $expectedIndexName = $this->tester->translateSourceIdentifierToIndexName(static::FIXTURE_SOURCE_IDENTIFIER);
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
        $this->tester->mockConfigMethod('getSupportedSourceIdentifiers', [static::FIXTURE_SOURCE_IDENTIFIER]);

        // Act
        $this->tester->getFacade()->installMapper(new NullLogger());

        // Assert
        $this->tester->assertIndexMapGenerated(static::FIXTURE_SOURCE_IDENTIFIER);
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
        $this->assertSame(SearchElasticsearchConfig::INDEX_CLOSE_STATE, $this->getIndexState($index));
    }

    /**
     * @return void
     */
    public function testCanCloseAllIndexes(): void
    {
        // Arrange
        /** @var \Elastica\Index $index */
        $index = $this->tester->haveIndex('dummy_index_name');
        $anotherIndex = $this->tester->haveIndex('another_dummy_index_name');
        $this->arrangeEnvironmentForMultiIndexTest([$index, $anotherIndex]);

        // Act
        $result = $this->tester->getFacade()->closeIndexes();

        // Assert
        $this->assertTrue($result);
        $this->assertAllIndexesAreOfExpectedState([$index, $anotherIndex], SearchElasticsearchConfig::INDEX_CLOSE_STATE);
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
        $this->assertSame(SearchElasticsearchConfig::INDEX_OPEN_STATE, $this->getIndexState($index));
    }

    /**
     * @return void
     */
    public function testCanOpenAllIndexes(): void
    {
        // Arrange
        /** @var \Elastica\Index $index */
        $index = $this->tester->haveIndex('dummy_index_name');
        $anotherIndex = $this->tester->haveIndex('another_dummy_index_name');
        $this->arrangeEnvironmentForMultiIndexTest([$index, $anotherIndex]);
        $this->tester->getFacade()->closeIndexes();

        // Act
        $result = $this->tester->getFacade()->openIndexes();

        // Assert
        $this->assertTrue($result);
        $this->assertAllIndexesAreOfExpectedState([$index, $anotherIndex], SearchElasticsearchConfig::INDEX_OPEN_STATE);
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
    public function testCanDeleteAllIndexes(): void
    {
        // Arrange
        $index = $this->tester->haveIndex('dummy_index_name');
        $anotherIndex = $this->tester->haveIndex('another_dummy_index_name');
        $this->arrangeEnvironmentForMultiIndexTest([$index, $anotherIndex]);

        // Act
        $this->tester->getFacade()->deleteIndexes();

        // Assert
        $this->assertAllIndexesAreDeleted([$index, $anotherIndex]);
    }

    /**
     * @param \Elastica\Index[] $indexes
     * @param string $expectedState
     *
     * @return void
     */
    protected function assertAllIndexesAreOfExpectedState(array $indexes, string $expectedState): void
    {
        $allIndexesAreOpen = true;

        foreach ($indexes as $index) {
            $indexState = $this->getIndexState($index);

            if ($indexState !== $expectedState) {
                $allIndexesAreOpen = false;

                break;
            }
        }

        $this->assertTrue($allIndexesAreOpen);
    }

    /**
     * @param \Elastica\Index[] $indexes
     *
     * @return void
     */
    protected function assertAllIndexesAreDeleted(array $indexes): void
    {
        $allIndexesAreDeleted = true;

        foreach ($indexes as $index) {
            if ($index->exists()) {
                $allIndexesAreDeleted = false;

                break;
            }
        }

        $this->assertTrue($allIndexesAreDeleted);
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
    public function testCanCopyIndexContentToAnotherIndex(): void
    {
        // Arrange
        $documentContent = ['foo' => 'bar'];
        $documentId = 'dummy_document';
        $sourceIndex = $this->tester->haveDocumentInIndex('source_index_name', $documentId, $documentContent);
        $destIndex = $this->tester->haveIndex('dest_index_name');

        // Act
        $this->tester->getFacade()->copyIndex(
            $this->tester->buildSearchContextTransferFromIndexName($sourceIndex->getName()),
            $this->tester->buildSearchContextTransferFromIndexName($destIndex->getName())
        );

        $destIndex->refresh();

        // Assert
        $this->assertDocumentInIndexHasExpectedContent($destIndex, $documentId, $documentContent);
    }

    /**
     * @param \Elastica\Index $index
     * @param string $documentId
     * @param array $expectedContent
     *
     * @return void
     */
    protected function assertDocumentInIndexHasExpectedContent(Index $index, string $documentId, array $expectedContent): void
    {
        $response = $index->request(sprintf('%s/%s', ElasticsearchHelper::DEFAULT_MAPPING_TYPE, $documentId), Request::GET);

        $this->assertSame($expectedContent, $response->getData()[static::DOCUMENT_CONTENT_KEY]);
    }

    /**
     * @return void
     */
    public function testCanCreateSnapshotRepository(): void
    {
        $this->skipIfCi();

        // Arrange
        $this->tester->mockFactoryMethod('createRepository', $this->createRepositoryMock());

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
        $this->skipIfCi();

        // Arrange
        $this->tester->mockFactoryMethod('createRepository', $this->createRepositoryMock());
        $this->tester->registerSnapshotRepository(static::REPOSITORY_NAME);

        // Act
        $result = $this->tester->getFacade()->existsSnapshotRepository(static::REPOSITORY_NAME);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SearchElasticsearch\Business\Snapshot\RepositoryInterface
     */
    protected function createRepositoryMock(): RepositoryInterface
    {
        $repositoryMock = $this->getMockBuilder(Repository::class)
            ->setConstructorArgs([$this->tester->getSnapshot()])
            ->setMethods(['buildRepositorySettings'])
            ->getMock();

        $repositoryMock->method('buildRepositorySettings')->willReturnCallback(function (): array {
            $settings = func_get_arg(2) ?? [];

            return array_merge(
                $settings,
                ['location' => $this->tester->getVirtualRepositoryLocation()]
            );
        });

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
     * @group somefoo
     *
     * @return void
     */
    public function testCanCreateSnapshot(): void
    {
        $this->skipIfCi();

        // Arrange
        $this->tester->addCleanupForSnapshotInRepository(static::REPOSITORY_NAME, static::SNAPSHOT_NAME);
        $this->tester->registerSnapshotRepository(static::REPOSITORY_NAME);

        // Act
        $result = $this->tester->getFacade()->createSnapshot(static::REPOSITORY_NAME, static::SNAPSHOT_NAME);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanCheckForSnapshotExistence(): void
    {
        $this->skipIfCi();

        // Arrange
        $this->tester->createSnapshotInRepository(static::REPOSITORY_NAME, static::SNAPSHOT_NAME);

        // Act
        $result = $this->tester->getFacade()->existsSnapshot(static::REPOSITORY_NAME, static::SNAPSHOT_NAME);

        // Assert
        $this->assertTrue($result);
        $this->assertTrue($this->tester->existsSnapshotInRepository(static::REPOSITORY_NAME, static::SNAPSHOT_NAME));
    }

    /**
     * @return void
     */
    public function testCanDeleteSnapshot(): void
    {
        $this->skipIfCi();

        // Arrange
        $this->tester->createSnapshotInRepository(static::REPOSITORY_NAME, static::SNAPSHOT_NAME);

        // Act
        $result = $this->tester->getFacade()->deleteSnapshot(static::REPOSITORY_NAME, static::SNAPSHOT_NAME);

        // Assert
        $this->assertTrue($result);
        $this->assertFalse($this->tester->existsSnapshotInRepository(static::REPOSITORY_NAME, static::SNAPSHOT_NAME));
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

    /**
     * @param \Elastica\Index[] $indexes
     *
     * @return void
     */
    protected function arrangeEnvironmentForMultiIndexTest(array $indexes): void
    {
        $indexNames = array_map(function (Index $index) {
            return $index->getName();
        }, $indexes);

        $sourceIdentifierMock = $this->createMock(SourceIdentifierInterface::class);
        $sourceIdentifierMock->method('translateToIndexName')->willReturnArgument(0);

        $this->tester->mockConfigMethod('getSupportedSourceIdentifiers', array_unique($indexNames));
        $this->tester->mockFactoryMethod('createSourceIdentifier', $sourceIdentifierMock);
        $this->tester->mockFactoryMethod('getConfig', $this->tester->getModuleConfig());
    }
}
