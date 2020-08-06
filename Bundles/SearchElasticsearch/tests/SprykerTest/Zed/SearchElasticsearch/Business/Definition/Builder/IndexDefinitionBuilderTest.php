<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Definition\Builder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\IndexDefinitionTransfer;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilder;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoaderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Merger\IndexDefinitionMergerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Definition
 * @group Builder
 * @group IndexDefinitionBuilderTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class IndexDefinitionBuilderTest extends Unit
{
    /**
     * @dataProvider buildReturnsResultProvider
     *
     * @param array $dummyIndexDefinitions
     *
     * @return void
     */
    public function testBuildReturnsArrayOfIndexDefinitionTransfers(array $dummyIndexDefinitions): void
    {
        $indexDefinitionLoader = $this->createIndexDefinitionLoaderMock();
        $indexDefinitionLoader->method('load')
            ->willReturn($dummyIndexDefinitions);

        $indexDefinitionBuilder = new IndexDefinitionBuilder(
            $indexDefinitionLoader,
            $this->createIndexDefinitionMergerMock(),
            $this->createIndexNameResolverMock()
        );

        $result = $indexDefinitionBuilder->build();

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(IndexDefinitionTransfer::class, $result[0]);
    }

    /**
     * @dataProvider buildReturnsResultProvider
     *
     * @param array $dummyIndexDefinitions
     *
     * @return void
     */
    public function testBuildReturnsResult(array $dummyIndexDefinitions): void
    {
        $indexDefinitionLoader = $this->createIndexDefinitionLoaderMock();
        $indexDefinitionLoader->method('load')
            ->willReturn($dummyIndexDefinitions);

        $indexDefinitionBuilder = new IndexDefinitionBuilder(
            $indexDefinitionLoader,
            $this->createIndexDefinitionMergerMock(),
            $this->createIndexNameResolverMock()
        );

        $result = $indexDefinitionBuilder->build();

        $this->assertEquals($dummyIndexDefinitions[0]['name'], $result[0]->getIndexName());
        $this->assertIsArray($result[0]->getMappings());
        $this->assertEquals($dummyIndexDefinitions[0]['definition']['mappings'], $result[0]->getMappings());
        $this->assertIsArray($result[0]->getSettings());
        $this->assertEquals($dummyIndexDefinitions[0]['definition']['settings'], $result[0]->getSettings());
    }

    /**
     * @return array
     */
    public function buildReturnsResultProvider(): array
    {
        $emptyIndexDefinition = [
            'name' => 'index',
            'definition' => [
                'settings' => [],
                'mappings' => [],
            ],
        ];
        $nonEmptyIndexDefinition = [
            'name' => 'index',
            'definition' => [
                'settings' => ['dummy settings'],
                'mappings' => ['dummy mappings'],
            ],
        ];

        return [
            'empty result' => [
                [$emptyIndexDefinition],
            ],
            'non-empty result' => [
                [$nonEmptyIndexDefinition],
            ],
        ];
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoaderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createIndexDefinitionLoaderMock(): IndexDefinitionLoaderInterface
    {
        return $this->createMock(IndexDefinitionLoaderInterface::class);
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Definition\Merger\IndexDefinitionMergerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createIndexDefinitionMergerMock(): IndexDefinitionMergerInterface
    {
        return $this->createMock(IndexDefinitionMergerInterface::class);
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createIndexNameResolverMock(): IndexNameResolverInterface
    {
        $indexNameResolverMock = $this->createMock(IndexNameResolverInterface::class);
        $indexNameResolverMock->method('resolve')->will($this->returnArgument(0));

        return $indexNameResolverMock;
    }
}
