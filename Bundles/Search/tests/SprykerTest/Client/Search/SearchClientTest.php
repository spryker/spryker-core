<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search;

use Codeception\Test\Unit;
use Elastica\Client;
use Elastica\ResultSet;
use Elastica\Status;
use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Search\Delegator\SearchDelegator;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface;
use Spryker\Client\Search\Model\Handler\ElasticsearchSearchHandler;
use Spryker\Client\Search\Plugin\Config\SearchConfig;
use Spryker\Client\Search\Plugin\Elasticsearch\Query\SearchKeysQuery;
use Spryker\Client\Search\Plugin\Elasticsearch\Query\SearchStringQuery;
use Spryker\Client\Search\SearchClient;
use Spryker\Client\Search\SearchContext\SourceIdentifierMapperInterface;
use Spryker\Client\Search\SearchFactory;
use Spryker\Client\SearchElasticsearch\Plugin\ElasticsearchSearchAdapterPlugin;

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
    /**
     * @var \Spryker\Client\Search\SearchClientInterface|\Spryker\Client\Kernel\AbstractClient
     */
    protected $searchClient;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->searchClient = new SearchClient();
    }

    /**
     * @return void
     */
    public function testGetSearchConfigShouldReturnTheSameInstance()
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
    public function testCheckConnection()
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
    public function testClientSearchWithoutResultFormatters()
    {
        $this->prepareSearchClientForSearchTest();

        /** @var \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\PHPUnit\Framework\MockObject\MockObject $queryMock */
        $queryMock = $this->getMockBuilder(QueryInterface::class)->getMock();

        $result = $this->searchClient->search($queryMock);
        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testClientSearchWithResultFormatters()
    {
        $this->prepareSearchClientForSearchTest();

        /** @var \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface|\PHPUnit\Framework\MockObject\MockObject $resultFormatterMock */
        $resultFormatterMock = $this
            ->getMockBuilder(ResultFormatterPluginInterface::class)
            ->setMethods(['getName', 'formatResult'])
            ->getMock();
        $resultFormatterMock
            ->method('getName')
            ->willReturn('fooResultFormatter');

        /** @var \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\PHPUnit\Framework\MockObject\MockObject $queryMock */
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
    public function testExpandQuery()
    {
        /** @var \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\PHPUnit\Framework\MockObject\MockObject $queryMock */
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
    public function testSearchKeys()
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
    public function testSearchString()
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
    protected function prepareSearchClientForSearchTest()
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
     * @group foobar
     */
    public function testCanWriteDocumentWhenNoSearchContextDefined(): void
    {
        // Arrange
        $documentId = 'foo_doc';
        $documentContent = ['foo' => 'bar'];
        $documentData = [$documentId => $documentContent];
        $typeName = 'page'; // source identifier
        $indexName = 'de_page_testing';
        $this->tester->haveIndex($indexName);
        /** @var \Spryker\Client\Search\SearchContext\SourceIdentifierMapperInterface|\PHPUnit\Framework\MockObject\MockObject $sourceIdentifierMapperMock */
        $sourceIdentifierMapperMock = $this->createMock(SourceIdentifierMapperInterface::class);
        $sourceIdentifierMapperMock->method('mapSourceIdentifier')
            ->willReturnCallback(function (SearchContextTransfer $searchContextTransfer) use ($indexName) {
                $searchContextTransfer->setElasticsearchContext(
                    (new ElasticsearchSearchContextTransfer())->setSourceName($indexName)
                );

                return $searchContextTransfer;
            });
        $searchDelegatorMock = $this->getMockBuilder(SearchDelegator::class)
            ->setConstructorArgs([[], $sourceIdentifierMapperMock])
            ->setMethods(['getSearchAdapterByIndexName'])
            ->getMock();
        $searchDelegatorMock->method('getSearchAdapterByIndexName')
            ->willReturn(new ElasticsearchSearchAdapterPlugin());
        $this->tester->mockFactoryMethod('createWriter', $searchDelegatorMock);

        // Act
        $this->tester->getClient()->write($documentData, $typeName, $indexName);

        // Assert
        $document = $this->getDocument($documentId, $indexName);
        $this->assertSame($documentContent, $document->getData());
    }

    protected function getDocument(string $documentId, string $indexName, string $typeName = '_doc')
    {
        return $this->tester
            ->getFactory()
            ->getElasticsearchClient()
            ->getIndex($indexName)
            ->getType($typeName)
            ->getDocument($documentId);
    }

    /**
     * @group foobar
     */
    public function testCanWriteDocumentWhenSearchContextDefined(): void
    {
        // Arrange
        $documentId = 'foo_doc';
        $documentContent = ['foo' => 'bar'];
        $documentData = [$documentId => $documentContent];
        $typeName = 'page'; // source identifier
        $indexName = 'de_page_testing';
        $this->tester->haveIndex($indexName);
        /** @var \Spryker\Client\Search\SearchContext\SourceIdentifierMapperInterface|\PHPUnit\Framework\MockObject\MockObject $sourceIdentifierMapperMock */
        $sourceIdentifierMapperMock = $this->createMock(SourceIdentifierMapperInterface::class);
        $sourceIdentifierMapperMock->method('mapSourceIdentifier')
            ->willReturnCallback(function (SearchContextTransfer $searchContextTransfer) use ($indexName) {
                $searchContextTransfer->setElasticsearchContext(
                    (new ElasticsearchSearchContextTransfer())->setSourceName($indexName)
                );

                return $searchContextTransfer;
            });
        $searchDelegatorMock = $this->getMockBuilder(SearchDelegator::class)
            ->setConstructorArgs([[], $sourceIdentifierMapperMock])
            ->setMethods(['getSearchAdapterByIndexName'])
            ->getMock();
        $searchDelegatorMock->method('getSearchAdapterByIndexName')
            ->willReturn(new ElasticsearchSearchAdapterPlugin());
        $this->tester->mockFactoryMethod('createWriter', $searchDelegatorMock);
        $searchContextTransfer = (new SearchContextTransfer())->setSourceIdentifier($typeName);

        // Act
        $this->tester->getClient()->write($documentData, $searchContextTransfer);

        // Assert
        $document = $this->getDocument($documentId, $indexName);
        $this->assertSame($documentContent, $document->getData());
    }

    /**
     * @group foobar
     *
     * @return void
     */
    public function testCanWriteDocumentInBcWay(): void
    {
        $documentId = 'foo_doc';
        $documentContent = ['foo' => 'bar'];
        $documentData = [$documentId => $documentContent];
        $typeName = 'page'; // source identifier
        $indexName = 'de_page_testing';
        $this->tester->haveIndex($indexName);

        $this->tester->getClient()->write($documentData, $typeName, $indexName);

        $document = $this->getDocument($documentId, $indexName, $typeName);
        $this->assertSame($documentContent, $document->getData());
    }
}
