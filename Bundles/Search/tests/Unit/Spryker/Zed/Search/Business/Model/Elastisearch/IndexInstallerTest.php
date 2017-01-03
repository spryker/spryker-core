<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Search\Business\Model\Elastisearch;

use Elastica\Client;
use Elastica\Index;
use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\IndexInstaller;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Search
 * @group Business
 * @group Model
 * @group Elastisearch
 * @group IndexInstallerTest
 */
class IndexInstallerTest extends PHPUnit_Framework_TestCase
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
            ->setMethods(['exists', 'create'])
            ->getMock();

        $indexMock->method('exists')->willReturn(false);
        $indexMock->expects($this->exactly(3))->method('create');

        $installer = new IndexInstaller(
            $this->createIndexDefinitionLoaderMock($indexDefinitions),
            $this->createElasticaClientMock($indexMock),
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
            $this->createIndexDefinition('foo'),
        ];

        $indexMock = $this->getMockBuilder(Index::class)
            ->disableOriginalConstructor()
            ->setMethods(['exists', 'create'])
            ->getMock();

        $indexMock->method('exists')->willReturn(true);
        $indexMock->expects($this->never())->method('create');

        $installer = new IndexInstaller(
            $this->createIndexDefinitionLoaderMock($indexDefinitions),
            $this->createElasticaClientMock($indexMock),
            $this->createMessengerMock()
        );

        $installer->install();
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
     * @return \PHPUnit_Framework_MockObject_MockBuilder|\Spryker\Zed\Messenger\Business\Model\MessengerInterface
     */
    protected function createMessengerMock()
    {
        $messengerMock = $this->getMockBuilder(MessengerInterface::class)
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
