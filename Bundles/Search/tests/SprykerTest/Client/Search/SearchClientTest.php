<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search;

use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Elastica\Client;
use Elastica\ResultSet;
use Elastica\Status;
use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Model\Handler\ElasticsearchSearchHandler;
use Spryker\Client\Search\Plugin\Config\SearchConfig;
use Spryker\Client\Search\Plugin\Elasticsearch\Query\SearchKeysQuery;
use Spryker\Client\Search\Plugin\Elasticsearch\Query\SearchStringQuery;
use Spryker\Client\Search\SearchClient;
use Spryker\Client\Search\SearchContext\SearchContextExpanderInterface;
use Spryker\Client\Search\SearchDependencyProvider;
use Spryker\Client\Search\SearchFactory;
use Spryker\Client\SearchElasticsearch\Plugin\ElasticsearchSearchAdapterPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextExpanderPluginInterface;
use SprykerTest\Shared\SearchElasticsearch\Helper\ElasticsearchHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group SearchClientTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Client\Search\SearchClientTester $tester
 */
class SearchClientTest extends Unit
{
    protected const INDEX_NAME = 'de_index_devtest';

    /**
     * @var \Spryker\Client\Search\SearchClientInterface|\Spryker\Client\Kernel\AbstractClient
     */
    protected $searchClient;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->skipIfElasticsearch7();

        parent::setUp();

        $this->searchClient = new SearchClient();
        $this->setupEnvironmentForSearchTesting();
    }

    /**
     * @return void
     */
    public function testGetSearchConfigShouldReturnTheSameInstance(): void
    {
        /** @var \Spryker\Client\Search\SearchFactory|\PHPUnit\Framework\MockObject\MockObject $searchFactoryMock */
        $searchFactoryMock = $this->getMockBuilder(SearchFactory::class)
            ->setMethods(['createSearchConfig'])
            ->getMock();
        $searchFactoryMock
            ->expects($this->once())
            ->method('createSearchConfig')
            ->willReturnCallback(function () {
                return $this->getMockBuilder(SearchConfig::class)->disableOriginalConstructor()->getMock();
            });

        $this->searchClient->setFactory($searchFactoryMock);

        $searchConfig1 = $this->searchClient->getSearchConfig();
        $searchConfig2 = $this->searchClient->getSearchConfig();

        $this->assertSame($searchConfig1, $searchConfig2);
    }

    /**
     * @return void
     */
    public function testCheckConnection(): void
    {
        $elasticaClientMock = $this
            ->getMockBuilder(Client::class)
            ->setMethods(['getStatus'])
            ->getMock();
        $elasticaClientMock
            ->method('getStatus')
            ->willReturn($this->getMockBuilder(Status::class)->disableOriginalConstructor()->getMock());

        /** @var \Spryker\Client\Search\SearchFactory|\PHPUnit\Framework\MockObject\MockObject $searchFactoryMock */
        $searchFactoryMock = $this->getMockBuilder(SearchFactory::class)
            ->setMethods(['getElasticsearchClient'])
            ->getMock();
        $searchFactoryMock
            ->method('getElasticsearchClient')
            ->willReturn($elasticaClientMock);

        $this->searchClient->setFactory($searchFactoryMock);

        $this->searchClient->checkConnection();
    }

    /**
     * @return void
     */
    public function testClientSearchWithoutResultFormatters(): void
    {
        $this->prepareSearchClientForSearchTest();

        /** @var \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\PHPUnit\Framework\MockObject\MockObject $queryMock */
        $queryMock = $this->getMockBuilder(QueryInterface::class)->getMock();

        $result = $this->searchClient->search($queryMock);
        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testClientSearchWithResultFormatters(): void
    {
        $this->prepareSearchClientForSearchTest();

        /** @var \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface|\PHPUnit\Framework\MockObject\MockObject $resultFormatterMock */
        $resultFormatterMock = $this
            ->getMockBuilder(ResultFormatterPluginInterface::class)
            ->setMethods(['getName', 'formatResult'])
            ->getMock();
        $resultFormatterMock
            ->method('getName')
            ->willReturn('fooResultFormatter');

        /** @var \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\PHPUnit\Framework\MockObject\MockObject $queryMock */
        $queryMock = $this->getMockBuilder(QueryInterface::class)->getMock();
        $resultFormatters = [
            $resultFormatterMock,
        ];

        $result = $this->searchClient->search($queryMock, $resultFormatters);

        $expectedResult = [
            $resultFormatterMock->getName() => null,
        ];

        $this->assertEquals($result, $expectedResult);
    }

    /**
     * @return void
     */
    public function testExpandQuery(): void
    {
        /** @var \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\PHPUnit\Framework\MockObject\MockObject $queryMock */
        $queryMock = $this->getMockBuilder(QueryInterface::class)->getMock();

        $queryExpanderMock = $this->getMockBuilder(QueryExpanderPluginInterface::class)
            ->setMethods(['expandQuery'])
            ->getMock();
        $queryExpanderMock
            ->expects($this->once())
            ->method('expandQuery')
            ->willReturn($this->getMockBuilder(QueryInterface::class)->getMock());

        $queryExpanders = [
            $queryExpanderMock,
        ];

        $result = $this->searchClient->expandQuery($queryMock, $queryExpanders);

        $this->assertInstanceOf(QueryInterface::class, $result);
    }

    /**
     * @return void
     */
    public function testSearchKeys(): void
    {
        $expectedQuery = new SearchKeysQuery('foo', 25, 100);

        /** @var \Spryker\Client\Search\SearchClient|\PHPUnit\Framework\MockObject\MockObject $clientMock */
        $clientMock = $this->getMockBuilder(SearchClient::class)
            ->setMethods(['search'])
            ->disableOriginalConstructor()
            ->getMock();
        $clientMock
            ->expects($this->once())
            ->method('search')
            ->with($expectedQuery);

        $clientMock->setFactory(new SearchFactory());

        $clientMock->searchKeys('foo', 25, 100);
    }

    /**
     * @return void
     */
    public function testSearchString(): void
    {
        $expectedQuery = new SearchStringQuery('foo:bar', 25, 100);

        /** @var \Spryker\Client\Search\SearchClient|\PHPUnit\Framework\MockObject\MockObject $clientMock */
        $clientMock = $this->getMockBuilder(SearchClient::class)
            ->setMethods(['search'])
            ->disableOriginalConstructor()
            ->getMock();
        $clientMock
            ->expects($this->once())
            ->method('search')
            ->with($expectedQuery);

        $clientMock->setFactory(new SearchFactory());

        $clientMock->searchQueryString('foo:bar', 25, 100);
    }

    /**
     * @return void
     */
    protected function prepareSearchClientForSearchTest(): void
    {
        $elasticsearchSearchHandlerMock = $this->getMockBuilder(ElasticsearchSearchHandler::class)
            ->setMethods(['executeQuery'])
            ->disableOriginalConstructor()
            ->getMock();
        $elasticsearchSearchHandlerMock->method('executeQuery')->willReturn(
            $this->getMockBuilder(ResultSet::class)->disableOriginalConstructor()->getMock()
        );

        /** @var \Spryker\Client\Search\SearchFactory|\PHPUnit\Framework\MockObject\MockObject $searchFactoryMock */
        $searchFactoryMock = $this->getMockBuilder(SearchFactory::class)
            ->setMethods(['createElasticsearchSearchHandler'])
            ->getMock();
        $searchFactoryMock
            ->method('createElasticsearchSearchHandler')
            ->willReturn($elasticsearchSearchHandlerMock);

        $this->searchClient->setFactory($searchFactoryMock);
    }

    /**
     * @return void
     */
    public function testCanWriteDocument(): void
    {
        // Arrange
        $documentId = 'document-id';
        $documentData = ['foo' => 'bar'];
        $dataSet = [
            $documentId => $documentData,
        ];
        $this->tester->haveIndex(static::INDEX_NAME);

        // Act
        $result = $this->tester->getClient()->write($dataSet, ElasticsearchHelper::DEFAULT_MAPPING_TYPE);

        // Assert
        $this->tester->assertDocumentExists($documentId, static::INDEX_NAME, $documentData);
    }

    /**
     * @return void
     */
    public function testCanWriteDocumentForSearchContext(): void
    {
        // Arrange
        $documentId = 'document-id';
        $documentData = ['foo' => 'bar'];
        $searchDocumentTransfer = $this->createSearchDocumentTransfer($documentId, $documentData);

        // Act
        $this->tester->getClient()->writeDocument($searchDocumentTransfer);

        // Assert
        $this->tester->assertDocumentExists($documentId, static::INDEX_NAME);
    }

    /**
     * @return void
     */
    public function testCanWriteMultipleDocuments(): void
    {
        // Arrange
        $documentId = 'new-document';
        $documentData = ['foo' => 'bar'];
        $anotherDocumentId = 'another-document';
        $anotherDocumentData = ['bar' => 'baz'];

        $searchDocumentTransfer = $this->createSearchDocumentTransfer($documentId, $documentData);
        $anotherSearchDocumentTransfer = $this->createSearchDocumentTransfer($anotherDocumentId, $anotherDocumentData);

        // Act
        $this->tester->getClient()->writeBulk([$searchDocumentTransfer, $anotherSearchDocumentTransfer]);

        // Assert
        foreach ([$documentId, $anotherDocumentId] as $currentDocumentId) {
            $this->tester->assertDocumentExists($currentDocumentId, static::INDEX_NAME);
        }
    }

    /**
     * @return void
     */
    public function testCanReadDocument(): void
    {
        // Arrange
        $documentId = 'new-document';
        $documentData = ['foo' => 'bar'];
        $this->tester->haveDocumentInIndex(static::INDEX_NAME, $documentId, $documentData);

        // Act
        $result = $this->tester->getClient()->read($documentId, ElasticsearchHelper::DEFAULT_MAPPING_TYPE);

        // Assert
        $this->assertSame($documentData, $result->getData());
    }

    /**
     * @return void
     */
    public function testCanReadDocumentForSearchContext(): void
    {
        // Arrange
        $documentId = 'document-id';
        $documentData = ['foo' => 'bar'];
        $this->tester->haveDocumentInIndex(static::INDEX_NAME, $documentId, $documentData);
        $searchDocumentTransfer = $this->createSearchDocumentTransfer($documentId);

        // Act
        $result = $this->tester->getClient()->readDocument($searchDocumentTransfer);

        // Assert
        $this->assertSame($documentData, $result->getData());
    }

    /**
     * @return void
     */
    public function testCanDeleteDocument(): void
    {
        // Arrange
        $documentId = 'document-id';
        $dataSet = [
            $documentId => [],
        ];
        $this->tester->haveDocumentInIndex(static::INDEX_NAME, $documentId);

        // Act
        $this->tester->getClient()->delete($dataSet, ElasticsearchHelper::DEFAULT_MAPPING_TYPE);

        // Assert
        $this->tester->assertDocumentDoesNotExist($documentId, static::INDEX_NAME);
    }

    /**
     * @return void
     */
    public function testCanDeleteDocumentForSearchContext(): void
    {
        // Arrange
        $documentId = 'document-id';
        $this->tester->haveDocumentInIndex(static::INDEX_NAME, $documentId);
        $searchDocumentTransfer = $this->createSearchDocumentTransfer($documentId);

        // Act
        $this->tester->getClient()->deleteDocument($searchDocumentTransfer);

        // Assert
        $this->tester->assertDocumentDoesNotExist($documentId, static::INDEX_NAME);
    }

    /**
     * @return void
     */
    public function testCanDeleteMultipleDocuments(): void
    {
        // Arrange
        $documentId = 'document-id';
        $anotherDocumentId = 'another-document-id';

        $searchDocumentTransfer = $this->createSearchDocumentTransfer($documentId);
        $anotherSearchDocumentTransfer = $this->createSearchDocumentTransfer($anotherDocumentId);

        $this->tester->haveDocumentInIndex(static::INDEX_NAME, $documentId);
        $this->tester->haveDocumentInIndex(static::INDEX_NAME, $anotherDocumentId);

        // Act
        $this->tester->getClient()->deleteBulk([$searchDocumentTransfer, $anotherSearchDocumentTransfer]);

        // Assert
        foreach ([$documentId, $anotherDocumentId] as $id) {
            $this->tester->assertDocumentDoesNotExist($id, static::INDEX_NAME);
        }
    }

    /**
     * @param string $documentId
     * @param array|string|null $documentData
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    protected function createSearchDocumentTransfer(string $documentId, $documentData = null): SearchDocumentTransfer
    {
        $searchContextTransfer = (new SearchContextTransfer())->setSourceIdentifier(ElasticsearchHelper::DEFAULT_MAPPING_TYPE);
        $searchDocumentTransfer = (new SearchDocumentTransfer())->setId($documentId)
            ->setSearchContext($searchContextTransfer);

        if ($documentData) {
            $searchDocumentTransfer->setData($documentData);
        }

        return $searchDocumentTransfer;
    }

    /**
     * @return void
     */
    protected function setupEnvironmentForSearchTesting(): void
    {
        $this->tester->setDependency(SearchDependencyProvider::PLUGINS_SEARCH_CONTEXT_EXPANDER, [
            $this->createSearchContextExpanderPluginMock(),
        ]);
        $this->tester->setDependency(SearchDependencyProvider::PLUGINS_CLIENT_ADAPTER, [
            $this->createElasticsearchSearchAdapterPluginMock(),
        ]);
    }

    /**
     * @return \Spryker\Client\Search\SearchContext\SearchContextExpanderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getSearchContextExpanderMock(): SearchContextExpanderInterface
    {
        $searchContextExpanderMock = $this->createMock(SearchContextExpanderInterface::class);
        $searchContextExpanderMock->method('expandSearchContext')
            ->willReturnCallback(function (SearchContextTransfer $searchContextTransfer) {
                $searchContextTransfer->setElasticsearchContext(
                    (new ElasticsearchSearchContextTransfer())->setIndexName(static::INDEX_NAME)->setTypeName(ElasticsearchHelper::DEFAULT_MAPPING_TYPE)
                );

                return $searchContextTransfer;
            });

        return $searchContextExpanderMock;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface
     */
    protected function createElasticsearchSearchAdapterPluginMock(): SearchAdapterPluginInterface
    {
        /** @var \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface $elasticsearchAdapterPluginMock */
        $elasticsearchAdapterPluginMock = Stub::make(ElasticsearchSearchAdapterPlugin::class, [
            'getClient' => $this->tester->getLocator()->searchElasticsearch()->client(),
            'isApplicable' => true,
        ]);

        return $elasticsearchAdapterPluginMock;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextExpanderPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSearchContextExpanderPluginMock(): SearchContextExpanderPluginInterface
    {
        $searchContextExpanderPluginMock = $this->createMock(SearchContextExpanderPluginInterface::class);
        $searchContextExpanderPluginMock->method('expandSearchContext')
            ->willReturnCallback(function (SearchContextTransfer $searchContextTransfer) {
                $searchContextTransfer->setElasticsearchContext(
                    (new ElasticsearchSearchContextTransfer())->setIndexName(static::INDEX_NAME)->setTypeName(ElasticsearchHelper::DEFAULT_MAPPING_TYPE)
                );

                return $searchContextTransfer;
            });

        return $searchContextExpanderPluginMock;
    }

    /**
     * @return void
     */
    protected function skipIfElasticsearch7(): void
    {
        if (!class_exists('\Elastica\Type')) {
            $this->markTestSkipped('This test is not suitable for Elasticsearch 7 or higher');
        }
    }
}
