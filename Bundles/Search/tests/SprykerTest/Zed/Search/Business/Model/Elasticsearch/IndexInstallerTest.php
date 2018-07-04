<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business\Model\Elasticsearch;

use Codeception\Test\Unit;
use Elastica\Client;
use Elastica\Index;
use Elastica\Response;
use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\IndexInstaller;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Model
 * @group Elasticsearch
 * @group IndexInstallerTest
 * Add your own group annotations below this line
 */
class IndexInstallerTest extends Unit
{
    /**
     * @return void
     */
    public function testIndexInstallerCreatesIndexesIfTheyNotExist()
    {
        $indexDefinitions = [
            $this->createIndexDefinition('foo'),
            $this->createIndexDefinition('bar'),
            $this->createIndexDefinition('baz'),
        ];

        $indexMock = $this->getMockBuilder(Index::class)
            ->disableOriginalConstructor()
            ->setMethods(['exists', 'request'])
            ->getMock();

        $indexMock->method('exists')->willReturn(false);
        $indexMock->expects($this->atLeastOnce())->method('request');

        $installer = new IndexInstaller(
            $this->createIndexDefinitionLoaderMock($indexDefinitions),
            $this->createElasticaClientMock($indexMock),
            $this->getBlacklist(),
            $this->createMessengerMock()
        );

        $installer->install();
    }

    /**
     * @return void
     */
    public function testIndexInstallerDoesNotCreatesIndexesIfTheyExist()
    {
        $indexDefinitions = [
            $this->createIndexDefinition('foo', [
                'index' => [
                    'config' => 1,
                ],
            ]),
        ];

        $indexMock = $this->getMockBuilder(Index::class)
            ->disableOriginalConstructor()
            ->setMethods(['exists', 'create', 'setSettings'])
            ->getMock();

        $indexMock->method('exists')->willReturn(true);
        $indexMock->expects($this->never())->method('create');
        $indexMock->method('setSettings')->willReturn(new Response(''));
        $indexMock->expects($this->once())->method('setSettings');

        $installer = new IndexInstaller(
            $this->createIndexDefinitionLoaderMock($indexDefinitions),
            $this->createElasticaClientMock($indexMock),
            $this->createMessengerMock(),
            $this->getBlacklist()
        );

        $installer->install();
    }

    /**
     * @return string[]
     */
    protected function getBlacklist()
    {
        return [
            'index.number_of_shards',
            'index.codec',
            'index.routing_partition_size',
            'index.shard.check_on_startup',
            'analysis',
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer[] $indexDefinitions
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface
     */
    protected function createIndexDefinitionLoaderMock(array $indexDefinitions)
    {
        $indexDefinitionLoader = $this->getMockBuilder(IndexDefinitionLoaderInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['loadIndexDefinitions'])
            ->getMock();

        $indexDefinitionLoader
            ->method('loadIndexDefinitions')
            ->willReturn($indexDefinitions);

        return $indexDefinitionLoader;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|\Elastica\Index $indexMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Elastica\Client
     */
    protected function createElasticaClientMock($indexMock)
    {
        $elasticaClientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIndex'])
            ->getMock();

        $elasticaClientMock
            ->method('getIndex')
            ->willReturn($indexMock);

        return $elasticaClientMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockBuilder|\Psr\Log\LoggerInterface
     */
    protected function createMessengerMock()
    {
        $messengerMock = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $messengerMock;
    }

    /**
     * @param string $name
     * @param array $settings
     * @param array $mappings
     *
     * @return \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer
     */
    protected function createIndexDefinition($name, array $settings = [], array $mappings = [])
    {
        $indexDefinition = new ElasticsearchIndexDefinitionTransfer();
        $indexDefinition
            ->setIndexName($name)
            ->setSettings($settings)
            ->setMappings($mappings);

        return $indexDefinition;
    }
}
