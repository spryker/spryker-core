<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business;

use Codeception\Test\Unit;
use Psr\Log\NullLogger;
use Spryker\Zed\Search\Communication\Plugin\Search\ElasticsearchIndexInstallerPlugin;
use Spryker\Zed\Search\SearchDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Facade
 * @group SearchFacadeTest
 * Add your own group annotations below this line
 */
class SearchFacadeTest extends Unit
{
    public const DE_INDEX_NAME_DEVTEST = 'de_index-name_devtest';

    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testInstallIndexInstallsIndices(): void
    {
        $this->tester->mockConfigMethod('getIndexNameMap', ['index-name' => static::DE_INDEX_NAME_DEVTEST]);
        $this->tester->mockConfigMethod('getClassTargetDirectory', codecept_output_dir());
        $this->tester->setDependency(SearchDependencyProvider::SEARCH_INSTALLER_PLUGINS, [
            new ElasticsearchIndexInstallerPlugin(),
        ]);

        $this->tester->mockConfigMethod('getJsonIndexDefinitionDirectories', [
            codecept_data_dir('Fixtures/Definition/Finder'),
        ]);

        $logger = new NullLogger();
        $this->tester->getFacade()->installIndices($logger);

        $client = $this->tester->getFactory()->getElasticsearchClient();
        $index = $client->getIndex(static::DE_INDEX_NAME_DEVTEST);

        $this->assertTrue($index->exists(), 'Index was expected to be installed but was not.');

        $this->tester->getFacade()->delete(self::DE_INDEX_NAME_DEVTEST);
    }

    /**
     * @return void
     */
    public function testGetTotalCountReturnsNumberOfDocumentsInAnIndex(): void
    {
        $this->tester->haveDocumentInIndex('foo');

        $response = $this->tester->getFacade()->getTotalCount('foo');

        $this->assertSame(1, $response, sprintf('Expected exactly one document but found "%s".', $response));
    }

    /**
     * @return void
     */
    public function testGetMetaDataReturnsArrayWithMetaDataOfAnIndex(): void
    {
        $this->tester->haveDocumentInIndex('foo');

        $response = $this->tester->getFacade()->getMetaData('foo');

        $this->assertIsArray($response, 'Expected exactly one document but found "%s".');
    }

    /**
     * @return void
     */
    public function testDeleteDeletesAnIndex(): void
    {
        $index = $this->tester->haveIndex('foo');
        $response = $this->tester->getFacade()->delete('foo');

        $this->assertTrue($response->isOk(), 'Delete response was expected to be true but is false.');
        $this->assertFalse($index->exists(), 'Index was expected to be deleted but still exists.');
    }
}
