<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Index\Copier;

use Codeception\Test\Unit;
use Spryker\Zed\SearchElasticsearch\Business\Index\Copier\IndexCopier;
use Spryker\Zed\SearchElasticsearch\Dependency\Guzzle\SearchElasticsearchToGuzzleClientInterface;
use Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchToUtilEncodingServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Index
 * @group Copier
 * @group IndexCopierTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class IndexCopierTest extends Unit
{
    /**
     * @return void
     */
    public function testCanCopyIndexToTarget(): void
    {
        $config = $this->tester->getModuleConfig();
        $sourceIndexName = 'source_index_name';
        $targetIndexName = 'target_index_name';
        $postData = $this->getPostData($sourceIndexName, $targetIndexName);
        $clientMock = $this->createClientMock($config->getReindexUrl(), $postData);

        $indexCopier = new IndexCopier(
            $clientMock,
            $config,
            $this->getUtilEncodingService()
        );
        $indexCopier->copyIndex(
            $this->tester->buildSearchContextTransferFromIndexName($sourceIndexName),
            $this->tester->buildSearchContextTransferFromIndexName($targetIndexName)
        );
    }

    /**
     * @param string $reindexUrl
     * @param array $postData
     *
     * @return \Spryker\Zed\SearchElasticsearch\Dependency\Guzzle\SearchElasticsearchToGuzzleClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createClientMock(string $reindexUrl, array $postData): SearchElasticsearchToGuzzleClientInterface
    {
        $clientMock = $this->createMock(SearchElasticsearchToGuzzleClientInterface::class);
        $clientMock->expects($this->once())
            ->method('post')
            ->with($reindexUrl, $postData)
            ->willReturn(true);

        return $clientMock;
    }

    /**
     * @param string $sourceIndexName
     * @param string $targetIndexName
     *
     * @return array
     */
    protected function getPostData(string $sourceIndexName, string $targetIndexName): array
    {
        $command = [
            'source' => [
                'index' => $sourceIndexName,
            ],
            'dest' => [
                'index' => $targetIndexName,
            ],
        ];

        return [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $this->getUtilEncodingService()->encodeJson($command),
        ];
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): SearchToUtilEncodingServiceInterface
    {
        return $this->tester->getFactory()->getUtilEncodingService();
    }
}
