<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Index;

use Codeception\Test\Unit;
use Elastica\Client;
use Elastica\Cluster;
use Elastica\Index as ElasticaIndex;
use Elastica\Request;
use Elastica\Response;
use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;
use Spryker\Zed\SearchElasticsearch\Business\Index\Index;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Index
 * @group IndexTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class IndexTest extends Unit
{
    protected const STORE = 'de';

    /**
     * @var \Elastica\Client|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $elasticaClientMock;

    /**
     * @var \Elastica\Cluster|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $clusterMock;

    /**
     * @var \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $indexNameResolverMock;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $configMock;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Index\Index
     */
    protected $index;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->clusterMock = $this->createMock(Cluster::class);
        $this->elasticaClientMock = $this->createElasticaClientMock();
        $this->indexNameResolverMock = $this->createMock(IndexNameResolverInterface::class);
        $this->configMock = $this->createMock(SearchElasticsearchConfig::class);

        $this->index = new Index(
            $this->elasticaClientMock,
            $this->indexNameResolverMock,
            $this->configMock
        );
    }

    /**
     * @dataProvider canCorrectlyResolveMultiIndexNamesDataProvider
     *
     * @param string $expectedAllIndexNamesFormattedString
     * @param array $supportedIndexNames
     *
     * @return void
     */
    public function testCanCorrectlyResolveMultiIndexNames(string $expectedAllIndexNamesFormattedString, array $supportedIndexNames): void
    {
        $this->clusterMock
            ->method('getIndexNames')
            ->willReturn($supportedIndexNames);
        $this->indexNameResolverMock
            ->method('resolve')
            ->willReturnCallback(function (string $sourceIdentifier) {
                return sprintf('%s_%s', static::STORE, $sourceIdentifier);
            });
        $this->configMock->method('getSupportedSourceIdentifiers')->willReturn([
            'foo',
            'bar',
            'baz',
        ]);
        $this->elasticaClientMock
            ->method('getIndex')
            ->with($expectedAllIndexNamesFormattedString)
            ->willReturn($this->createElasticaIndexMock());

        $this->index->openIndexes();
    }

    /**
     * @return array
     */
    public function canCorrectlyResolveMultiIndexNamesDataProvider(): array
    {
        return [
            'all indexes supported' => ['de_foo,de_bar,de_baz', ['de_foo', 'de_bar', 'de_baz']],
            'one index supported' => ['de_bar', ['de_bar']],
            'no indexes supported' => ['', []],
        ];
    }

    /**
     * @return void
     */
    public function testCanResolveIndexNameFromSearchContextTransfer(): void
    {
        $indexName = 'index-name';
        $searchContextTransfer = $this->buildSearchContextTransferForIndexName($indexName);
        $this->elasticaClientMock->expects($this->once())
            ->method('getIndex')
            ->with($indexName)
            ->willReturn($this->createElasticaIndexMock());

        $this->index->openIndex($searchContextTransfer);
    }

    /**
     * @return void
     */
    public function testCanCopyIndexFromSourceToTarget(): void
    {
        $reindexUrl = 'reindex-url';
        $sourceIndexName = 'source-index-name';
        $targetIndexName = 'target-index-name';
        $sourceSearchContextTransfer = $this->buildSearchContextTransferForIndexName($sourceIndexName);
        $targetSearchContextTransfer = $this->buildSearchContextTransferForIndexName($targetIndexName);

        $expectedRequestData = [
            'source' => [
                'index' => $sourceIndexName,
            ],
            'dest' => [
                'index' => $targetIndexName,
            ],
        ];
        $this->configMock->method('getReindexUrl')->willReturn($reindexUrl);

        $this->elasticaClientMock->expects($this->once())
            ->method('request')
            ->with($reindexUrl, Request::POST, $expectedRequestData)
            ->willReturn(
                $this->createResponseMock()
            );

        $this->index->copyIndex($sourceSearchContextTransfer, $targetSearchContextTransfer);
    }

    /**
     * @param string $indexName
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected function buildSearchContextTransferForIndexName(string $indexName): SearchContextTransfer
    {
        $elasticsearchContext = (new ElasticsearchSearchContextTransfer())->setIndexName($indexName);

        return (new SearchContextTransfer())->setElasticsearchContext($elasticsearchContext);
    }

    /**
     * @return \Elastica\Index|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createElasticaIndexMock(): ElasticaIndex
    {
        $elasticaIndexMock = $this->createMock(ElasticaIndex::class);
        $elasticaIndexMock->method('open')->willReturn($this->createResponseMock());

        return $elasticaIndexMock;
    }

    /**
     * @return \Elastica\Response|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createResponseMock(): Response
    {
        $responseMock = $this->createMock(Response::class);
        $responseMock->method('isOk')->willReturn(true);

        return $responseMock;
    }

    /**
     * @return \Elastica\Client|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createElasticaClientMock(): Client
    {
        $elasticaClientMock = $this->createMock(Client::class);
        $elasticaClientMock->method('getCluster')->willReturn($this->clusterMock);

        return $elasticaClientMock;
    }
}
