<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business;

use Codeception\Test\Unit;
use Psr\Log\NullLogger;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Facade
 * @group SearchFacadeBCTest
 * Add your own group annotations below this line
 *
 * @deprecated Use `\SprykerTest\Zed\Search\Business\SearchFacadeTest` instead.
 */
class SearchFacadeBCTest extends Unit
{
    public const INDEX_NAME = 'de_search_devtest';

    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDeleteDeletesAnIndex(): void
    {
        $index = $this->tester->haveIndex(static::INDEX_NAME);
        $response = $this->tester->getFacade()->delete();

        $this->assertTrue($response->isOk(), 'Delete response was expected to be true but is false.');
        $this->assertFalse($index->exists(), 'Index was expected to be deleted but still exists.');
    }

    /**
     * @return void
     */
    public function testGetTotalCountReturnsNumberOfDocumentsInAnIndex(): void
    {
        $this->tester->haveDocumentInIndex(static::INDEX_NAME);

        $response = $this->tester->getFacade()->getTotalCount();

        $this->assertSame(1, $response, sprintf('Expected exactly one document but found "%s".', $response));
    }

    /**
     * @return void
     */
    public function testInstallIndexInstallsIndices(): void
    {
        $this->tester->mockConfigMethod('getClassTargetDirectory', codecept_output_dir());
        $this->tester->mockConfigMethod('getJsonIndexDefinitionDirectories', [
            codecept_data_dir('Fixtures/Definition/FinderBC'),
        ]);

        $logger = new NullLogger();
        $this->tester->getFacade()->install($logger);

        $client = $this->tester->getFactory()->getElasticsearchClient();
        $index = $client->getIndex(static::INDEX_NAME);

        $this->assertTrue($index->exists(), 'Index was expected to be installed but was not.');

        $this->tester->getFacade()->delete(static::INDEX_NAME);
    }
}
