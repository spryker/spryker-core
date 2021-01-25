<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\SearchElasticsearch\tests\SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index;

use Codeception\Test\Unit;
use Elastica\Client;
use Elastica\Index;
use Generated\Shared\Transfer\IndexDefinitionTransfer;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface;

/**
 * Auto-generated group annotations
 *
 * @group Spryker
 * @group SearchElasticsearch
 * @group tests
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Installer
 * @group Index
 * @group AbstractIndexTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
abstract class AbstractIndexTest extends Unit
{
    /**
     * @param string[] $mappings
     *
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMappingBuilderMock(array $mappings = []): MappingBuilderInterface
    {
        $mappingMock = $this->tester->createMappingMock([
            'toArray' => function () use ($mappings) {
                return $mappings;
            },
        ]);
        $mappingBuilder = $this->createMock(MappingBuilderInterface::class);
        $mappingBuilder->method('buildMapping')->willReturn($mappingMock);

        return $mappingBuilder;
    }

    /**
     * @param \Elastica\Index|\PHPUnit\Framework\MockObject\MockObject|null $indexMock
     *
     * @return \Elastica\Client|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createClientMock(?Index $indexMock = null): Client
    {
        $clientMock = $this->createMock(Client::class);
        $clientMock->method('getIndex')->willReturn(
            $indexMock ?? $this->createIndexMock()
        );

        return $clientMock;
    }

    /**
     * @return \Elastica\Index|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createIndexMock(): Index
    {
        return $this->createMock(Index::class);
    }

    /**
     * @param string $indexName
     * @param array $mappings
     * @param array $settings
     *
     * @return \Generated\Shared\Transfer\IndexDefinitionTransfer
     */
    protected function createIndexDefinitionTransfer(string $indexName = 'index_name', array $mappings = [[]], array $settings = []): IndexDefinitionTransfer
    {
        return (new IndexDefinitionTransfer())
            ->setIndexName($indexName)
            ->setMappings($mappings)
            ->setSettings($settings);
    }
}
