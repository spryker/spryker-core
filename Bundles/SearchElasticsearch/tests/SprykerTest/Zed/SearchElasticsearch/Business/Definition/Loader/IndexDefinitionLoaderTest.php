<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Definition\Loader;

use Codeception\Test\Unit;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoader;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Definition
 * @group Loader
 * @group IndexDefinitionLoaderTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class IndexDefinitionLoaderTest extends Unit
{
    protected const SCHEMA_DEFINITION_FILE_NAME = 'index-name';
    protected const SCHEMA_DEFINITION_FILE_EXTENSION = 'json';

    protected const INDEX_DEFINITION_KEY_NAME = 'name';
    protected const INDEX_DEFINITION_KEY_DEFINITION = 'definition';

    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoaderInterface
     */
    protected $indexDefinitionLoader;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->indexDefinitionLoader = new IndexDefinitionLoader(
            $this->createSchemaDefinitionFinderMock(),
            $this->createIndexDefinitionReaderMock()
        );
    }

    /**
     * @return void
     */
    public function testLoadReturnsAnArrayOfDefinitions(): void
    {
        $indexDefinitions = $this->indexDefinitionLoader->load();

        $this->assertIsArray($indexDefinitions);
        $this->assertCount(1, $indexDefinitions);
    }

    /**
     * @return void
     */
    public function testCanLoadIndexDefinitionsFromFile(): void
    {
        $result = $this->indexDefinitionLoader->load();

        $this->assertArrayHasKey(static::INDEX_DEFINITION_KEY_NAME, $result[0]);
        $this->assertArrayHasKey(static::INDEX_DEFINITION_KEY_DEFINITION, $result[0]);
        $this->assertEquals(static::SCHEMA_DEFINITION_FILE_NAME, $result[0][static::INDEX_DEFINITION_KEY_NAME]);
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSchemaDefinitionFinderMock(): SchemaDefinitionFinderInterface
    {
        $splFileInfoMock = $this->createMock(SplFileInfo::class);
        $splFileInfoMock->method('getFilename')->willReturn(
            sprintf('%s.%s', static::SCHEMA_DEFINITION_FILE_NAME, static::SCHEMA_DEFINITION_FILE_EXTENSION)
        );
        $splFileInfoMock->method('getExtension')->willReturn(static::SCHEMA_DEFINITION_FILE_EXTENSION);

        $finderMock = $this->createMock(Finder::class);
        $finderMock->method('getIterator')->willReturnCallback(function () use ($splFileInfoMock) {
            yield $splFileInfoMock;
        });

        $schemaDefinitionFinderMock = $this->createMock(SchemaDefinitionFinderInterface::class);
        $schemaDefinitionFinderMock->method('find')->willReturn($finderMock);

        return $schemaDefinitionFinderMock;
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createIndexDefinitionReaderMock(): IndexDefinitionReaderInterface
    {
        return $this->createMock(IndexDefinitionReaderInterface::class);
    }
}
