<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Client;

use Elastica\Client;
use Elastica\ResultSet;
use Elastica\Status;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface;
use Spryker\Client\Search\Model\Handler\ElasticsearchSearchHandler;
use Spryker\Client\Search\Plugin\Config\SearchConfig;
use Spryker\Client\Search\Plugin\Elasticsearch\Query\SearchKeysQuery;
use Spryker\Client\Search\SearchClient;
use Spryker\Client\Search\SearchFactory;

/**
 * @group Client
 * @group Search
 */
class SearchClientTest extends \PHPUnit_Framework_TestCase
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
        $this->searchClient = new SearchClient();
    }

    /**
     * @return void
     */
    public function testGetSearchConfigShouldReturnTheSameInstance()
    {
        /** @var \Spryker\Client\Search\SearchFactory|\PHPUnit_Framework_MockObject_MockObject $searchFactoryMock */
        $searchFactoryMock = $this->getMockBuilder(SearchFactory::class)
            ->setMethods(['createSearchConfig'])
            ->getMock();
        $searchFactoryMock
            ->expects($this->once())
            ->method('createSearchConfig')
            ->willReturnCallback(function () {
                return $this->getMockWithoutInvokingTheOriginalConstructor(SearchConfig::class);
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
            ->willReturn($this->getMockWithoutInvokingTheOriginalConstructor(Status::class));

        /** @var \Spryker\Client\Search\SearchFactory|\PHPUnit_Framework_MockObject_MockObject $searchFactoryMock */
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

        /** @var \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\PHPUnit_Framework_MockObject_MockObject $queryMock */
        $queryMock = $this->getMock(QueryInterface::class);

        $result = $this->searchClient->search($queryMock);
        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testClientSearchWithResultFormatters()
    {
        $this->prepareSearchClientForSearchTest();

        /** @var \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface|\PHPUnit_Framework_MockObject_MockObject $resultFormatterMock */
        $resultFormatterMock = $this
            ->getMockBuilder(ResultFormatterPluginInterface::class)
            ->setMethods(['getName', 'formatResult'])
            ->getMock();
        $resultFormatterMock
            ->method('getName')
            ->willReturn('fooResultFormatter');

        /** @var \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\PHPUnit_Framework_MockObject_MockObject $queryMock */
        $queryMock = $this->getMock(QueryInterface::class);
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
        /** @var \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\PHPUnit_Framework_MockObject_MockObject $queryMock */
        $queryMock = $this->getMock(QueryInterface::class);

        $queryExpanderMock = $this->getMockBuilder(QueryExpanderPluginInterface::class)
            ->setMethods(['expandQuery'])
            ->getMock();
        $queryExpanderMock
            ->expects($this->once())
            ->method('expandQuery')
            ->willReturn($this->getMock(QueryInterface::class));

        $queryExpanders = [
            $queryExpanderMock
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

        /** @var \Spryker\Client\Search\SearchClient|\PHPUnit_Framework_MockObject_MockObject $clientMock */
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
    protected function prepareSearchClientForSearchTest()
    {
        $elasticsearchSearchHandlerMock = $this->getMockBuilder(ElasticsearchSearchHandler::class)
            ->setMethods(['executeQuery'])
            ->disableOriginalConstructor()
            ->getMock();
        $elasticsearchSearchHandlerMock->method('executeQuery')->willReturn(
            $this->getMockWithoutInvokingTheOriginalConstructor(ResultSet::class)
        );

        /** @var \Spryker\Client\Search\SearchFactory|\PHPUnit_Framework_MockObject_MockObject $searchFactoryMock */
        $searchFactoryMock = $this->getMockBuilder(SearchFactory::class)
            ->setMethods(['createElasticsearchSearchHandler'])
            ->getMock();
        $searchFactoryMock
            ->method('createElasticsearchSearchHandler')
            ->willReturn($elasticsearchSearchHandlerMock);

        $this->searchClient->setFactory($searchFactoryMock);
    }

}
