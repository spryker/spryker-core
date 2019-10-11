<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business\Definition\Finder;

use Codeception\Test\Unit;
use Spryker\Zed\Search\Business\Definition\Finder\IndexDefinitionFinder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Definition
 * @group Finder
 * @group IndexDefinitionFinderTest
 * Add your own group annotations below this line
 */
class IndexDefinitionFinderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindReturnsOnlyFilesWhichAreNotNamedSearch(): void
    {
        $this->tester->mockConfigMethod('getJsonIndexDefinitionDirectories', $this->getFixturesDirectory());

        $indexDefinitionFinder = new IndexDefinitionFinder($this->tester->getModuleConfig());
        $splFileInfoCollection = $indexDefinitionFinder->find();

        $this->assertCount(1, $splFileInfoCollection);

        foreach ($splFileInfoCollection as $fileInfo) {
            $this->assertSame('index-name.json', $fileInfo->getFilename());
        }
    }

    /**
     * @return string
     */
    protected function getFixturesDirectory(): string
    {
        return codecept_data_dir('Fixtures/Definition/Finder/');
    }
}
