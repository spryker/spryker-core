<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business\Definition\Loader;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Definition
 * @group Loader
 * @group IndexDefinitionLoaderTest
 * Add your own group annotations below this line
 */
class IndexDefinitionLoaderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testLoadReturnsAnArrayOfDefinitions(): void
    {
        $this->tester->mockConfigMethod('getJsonIndexDefinitionDirectories', $this->getFixturesDirectory());
        $indexDefinitionLoader = $this->tester->getFactory()->createIndexDefinitionLoader();

//        $indexDefinitionLoader = new IndexDefinitionLoader(
//            new IndexDefinitionFinder($this->tester->getModuleConfig()),
//            new IndexDefinitionReader($searchToUtilEncodingBridge)
//        );

        $indexDefinitions = $indexDefinitionLoader->load();

        $this->assertIsArray($indexDefinitions);
        $this->assertCount(1, $indexDefinitions);
    }

    /**
     * @return string
     */
    protected function getFixturesDirectory(): string
    {
        return codecept_data_dir('Fixtures/Definition/Finder');
    }
}
