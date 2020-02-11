<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index\Update;

use Elastica\Index;
use Elastica\Type\Mapping;
use Generated\Shared\Transfer\IndexDefinitionTransfer;
use Psr\Log\NullLogger;
use Spryker\SearchElasticsearch\tests\SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index\AbstractIndexTest;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update\IndexUpdater;

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

        $indexInstaller = new IndexUpdater(
            $clientMock,
            $this->createMappingBuilderMock()
        );

        $isAccepted = $indexInstaller->accept(new IndexDefinitionTransfer());

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
     * @param int $expectedNumberOfUpdates
     * @param string $indexName
     * @param array $mappings
     *
     * @return void
     */
    public function testCanUpdateMappings(int $expectedNumberOfUpdates, string $indexName, array $mappings): void
    {
        $indexMock = $this->createIndexMock();
        $indexMock->method('getName')->willReturn($indexName);
        $clientMock = $this->createClientMock($indexMock);
        $normalizedMappingData = $this->normalizeMappingDataForTest($indexMock, $mappings);
        $mappingMock = $this->createMock(Mapping::class);

        $mappingBuilderMock = $this->createMappingBuilderMock();
        $mappingBuilderMock->expects($this->exactly($expectedNumberOfUpdates))
            ->method('buildMapping')
            ->withConsecutive(...$normalizedMappingData)
            ->willReturn($mappingMock);

        $indexUpdater = new IndexUpdater(
            $clientMock,
            $mappingBuilderMock
        );

        $indexDefinitionTransfer = (new IndexDefinitionTransfer())
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
                1,
                'index-name',
                [
                    'foo' => ['foo'],
                ],
            ],
            'two mappings' => [
                2,
                'another-index-name',
                [
                    'foo' => ['foo'],
                    'bar' => ['bar'],
                ],
            ],
            'three mapping' => [
                3,
                'yet-another-index-name',
                [
                    'foo' => ['foo'],
                    'bar' => ['bar'],
                    'baz' => ['baz'],
                ],
            ],
        ];
    }

    /**
     * @param \Elastica\Index $index
     * @param array $mappingData
     *
     * @return array
     */
    protected function normalizeMappingDataForTest(Index $index, array $mappingData): array
    {
        $normalizedMappingData = [];

        foreach ($mappingData as $key => $mapping) {
            $normalizedMappingData[] = [$index, $key, $mapping];
        }

        return $normalizedMappingData;
    }
}
