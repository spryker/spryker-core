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
use Spryker\Zed\Search\SearchConfig;

/**
 * Auto-generated group annotations
 *
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
            $this->createMessengerMock(),
            $this->createSearchConfigMock()
        );

        $installer->install();
    }

    /**
     * @uses \Elastica\Index::exists()
     * @uses \Elastica\Index::create()
     * @uses \Elastica\Index::setSettings()
     *
     * @return void
     */
    public function testIndexInstallerDoesNotCreatesIndexesIfTheyExist()
    {
        $indexMock = $this->getMockBuilder(Index::class)
            ->disableOriginalConstructor()
            ->setMethods(['exists', 'create', 'setSettings'])
            ->getMock();

        $indexMock->method('exists')->willReturn(true);
        $indexMock->expects($this->never())->method('create');

        $indexMock->method('setSettings')->willReturn(new Response(''));
        $indexMock->expects($this->once())->method('setSettings');

        $indexInstallerMock = $this->createIndexInstallerMock($indexMock);
        $indexInstallerMock->install();
    }

    /**
     * @uses IndexInstaller::getIndexState()
     *
     * @param \PHPUnit\Framework\MockObject\MockObject|\Elastica\Index $indexMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Search\Business\Model\SearchInstallerInterface
     */
    protected function createIndexInstallerMock($indexMock)
    {
        $indexDefinitions = [
            $this->createIndexDefinition('foo', [
                'index' => [
                    'config' => 1,
                ],
            ]),
        ];

        return $this->getMockBuilder(IndexInstaller::class)
            ->setConstructorArgs([
                $this->createIndexDefinitionLoaderMock($indexDefinitions),
                $this->createElasticaClientMock($indexMock),
                $this->createMessengerMock(),
                $this->createSearchConfigMock(),
            ])
            ->setMethods(['getIndexState'])
            ->getMock();
    }

    /**
     * @uses IndexDefinitionLoaderInterface::loadIndexDefinitions()
     *
     * @param \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer[] $indexDefinitions
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface
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
     * @uses SearchConfig::getBlacklistSettingsForIndexUpdate()
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Search\SearchConfig
     */
    protected function createSearchConfigMock()
    {
        $searchConfigMock = $this->getMockBuilder(SearchConfig::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBlacklistSettingsForIndexUpdate'])
            ->getMock();

        return $searchConfigMock;
    }

    /**
     * @uses Client::getIndex()
     *
     * @param \PHPUnit\Framework\MockObject\MockObject|\Elastica\Index $indexMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Elastica\Client
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Psr\Log\LoggerInterface
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
