<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Definition\Finder;

use Codeception\Test\Unit;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Definition
 * @group Finder
 * @group SchemaDefinitionFinderTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class SchemaDefinitionFinderTest extends Unit
{
    protected const SCHEMA_DEFINITION_FILE_NAME = 'index-name.json';

    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCanFindSchemaDefinitionFiles(): void
    {
        $this->tester->mockConfigMethod('getJsonSchemaDefinitionDirectories', $this->tester->getFixturesSchemaDirectory());

        $schemaDefinitionFinder = new SchemaDefinitionFinder($this->tester->getModuleConfig());
        $splFileInfoCollection = $schemaDefinitionFinder->find();

        $this->assertCount(1, $splFileInfoCollection);

        foreach ($splFileInfoCollection as $fileInfo) {
            $this->assertSame(static::SCHEMA_DEFINITION_FILE_NAME, $fileInfo->getFilename());
        }
    }
}
