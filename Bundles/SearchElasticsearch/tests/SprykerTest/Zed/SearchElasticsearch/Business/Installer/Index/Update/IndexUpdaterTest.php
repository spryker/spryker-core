<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index\Update;

use Elastica\Client;
use Psr\Log\NullLogger;
use Spryker\SearchElasticsearch\tests\SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index\AbstractIndexTest;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update\AbstractIndexUpdater;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update\IndexUpdater;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update\MappingTypeAwareIndexUpdater;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Installer
 * @group Index
 * @group Update
 * @group IndexUpdaterTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class IndexUpdaterTest extends AbstractIndexTest
{
    /**
     * @dataProvider isAcceptedWhenIndexExistsProvider
     *
     * @param bool $expectedIsAccepted
     * @param bool $isIndexExists
     *
     * @return void
     */
    public function testIsAcceptedWhenIndexExists(bool $expectedIsAccepted, bool $isIndexExists): void
    {
        $indexMock = $this->createIndexMock();
        $indexMock->method('exists')->willReturn($isIndexExists);
        $clientMock = $this->createClientMock($indexMock);

        $indexInstaller = $this->createIndexUpdated($clientMock, $this->createMappingBuilderMock());

        $isAccepted = $indexInstaller->accept($this->createIndexDefinitionTransfer());

        $this->assertEquals($expectedIsAccepted, $isAccepted);
    }

    /**
     * @return array
     */
    public function isAcceptedWhenIndexExistsProvider(): array
    {
        return [
            'index exists' => [true, true],
            'index does not exist' => [false, false],
        ];
    }

    /**
     * @dataProvider canUpdateMappingsProvider
     *
     * @param string $indexName
     * @param array $mappings
     *
     * @return void
     */
    public function testCanUpdateMappings(string $indexName, array $mappings): void
    {
        $indexMock = $this->createIndexMock();
        $indexMock->method('getName')->willReturn($indexName);
        $clientMock = $this->createClientMock($indexMock);
        $mappingMock = $this->tester->createMappingMock([
            'toArray' => function () use ($mappings) {
                return $mappings;
            },
        ]);

        $mappingBuilderMock = $this->createMappingBuilderMock();
        $mappingBuilderMock->expects($this->once())
            ->method('buildMapping')
            ->with($mappings, $indexMock)
            ->willReturn($mappingMock);

        $indexUpdater = $this->createIndexUpdated($clientMock, $mappingBuilderMock);

        $indexDefinitionTransfer = ($this->createIndexDefinitionTransfer())
            ->setMappings($mappings)
            ->setIndexName($indexName);

        $indexUpdater->run($indexDefinitionTransfer, new NullLogger());
    }

    /**
     * @return array
     */
    public function canUpdateMappingsProvider(): array
    {
        return [
            'one mapping' => [
                'index-name',
                [
                    'foo' => ['foo' => ['foo']],
                ],
            ],
            'two mappings' => [
                'another-index-name',
                [
                    'foo' => ['foo' => ['foo']],
                    'bar' => ['bar' => ['bar']],
                ],
            ],
            'three mappings' => [
                'yet-another-index-name',
                [
                    'foo' => ['foo' => ['foo']],
                    'bar' => ['bar' => ['bar']],
                    'baz' => ['baz' => ['baz']],
                ],
            ],
        ];
    }

    /**
     * @param \Elastica\Client|\PHPUnit\Framework\MockObject\MockObject $elasticaClient
     * @param \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface|\PHPUnit\Framework\MockObject\MockObject $mappingBuilder
     *
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update\AbstractIndexUpdater
     */
    protected function createIndexUpdated(Client $elasticaClient, MappingBuilderInterface $mappingBuilder): AbstractIndexUpdater
    {
        if ($this->tester->supportsMappingTypes()) {
            return new MappingTypeAwareIndexUpdater(
                $elasticaClient,
                $mappingBuilder
            );
        }

        return new IndexUpdater($elasticaClient, $mappingBuilder);
    }
}
