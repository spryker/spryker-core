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
use Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade;
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

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade
     */
    protected $searchElasticsearchFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->searchElasticsearchFacade = new SearchElasticsearchFacade();
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
        $this->searchElasticsearchFacade->setFactory(
            $this->tester->getSearchElasticsearchBusinessFactory()
        );

        // Act
        $this->searchElasticsearchFacade->install(new NullLogger());

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
        $this->searchElasticsearchFacade->setFactory(
            $this->tester->getSearchElasticsearchBusinessFactory()
        );

        // Act
        $this->searchElasticsearchFacade->installMapper(new NullLogger());

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
}
