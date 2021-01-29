<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch;

use Codeception\Actor;
use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchBusinessFactory;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchBusinessFactory getFactory()
 * @method \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig getModuleConfig()
 *
 * @SuppressWarnings(PHPMD)
 */
class SearchElasticsearchZedTester extends Actor
{
    use _generated\SearchElasticsearchZedTesterActions;

    protected const INDEX_MAP_DESTINATION_DIR = 'IndexMap';

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchBusinessFactory
     */
    public function getSearchElasticsearchBusinessFactory(): SearchElasticsearchBusinessFactory
    {
        $searchElasticsearchBusinessFactory = new SearchElasticsearchBusinessFactory();
        $searchElasticsearchBusinessFactory->setConfig(
            $this->getModuleConfig()
        );

        return $searchElasticsearchBusinessFactory;
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return string
     */
    public function translateSourceIdentifierToIndexName(string $sourceIdentifier): string
    {
        return $this->getSearchElasticsearchBusinessFactory()
            ->createSourceIdentifier()
            ->translateToIndexName($sourceIdentifier);
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return void
     */
    public function assertIndexMapGenerated(string $sourceIdentifier): void
    {
        $indexMapFileName = $this->getIndexMapFileNameFromSourceIdentifier($sourceIdentifier);
        $indexMapDirectoryContents = $this->getVirtualDirectoryContents(static::INDEX_MAP_DESTINATION_DIR);

        $this->assertTrue(in_array($indexMapFileName, $indexMapDirectoryContents, true));
    }

    /**
     * @return string[]
     */
    public function getFixturesSchemaDirectory(): array
    {
        return [codecept_data_dir('Fixtures/Definition/Schema/')];
    }

    /**
     * @return string
     */
    public function getFixturesIndexMapDirectory(): string
    {
        return $this->getVirtualDirectory() . static::INDEX_MAP_DESTINATION_DIR . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return string
     */
    public function getIndexMapFileNameFromSourceIdentifier(string $sourceIdentifier): string
    {
        $classPrefix = $this->normalizeToClassPrefix($sourceIdentifier);

        return $classPrefix . 'IndexMap.php';
    }

    /**
     * @param string|null $indexName
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function buildSearchContextTransferFromIndexName(?string $indexName): SearchContextTransfer
    {
        $elasticsearchSearchContext = new ElasticsearchSearchContextTransfer();
        $elasticsearchSearchContext->setIndexName($indexName);

        $searchContextTransfer = new SearchContextTransfer();
        $searchContextTransfer->setElasticsearchContext($elasticsearchSearchContext);

        return $searchContextTransfer;
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return string
     */
    protected function normalizeToClassPrefix(string $sourceIdentifier): string
    {
        $normalized = preg_replace('/\\W+/', '_', $sourceIdentifier);
        $normalized = trim($normalized, '_');

        $filter = new UnderscoreToCamelCase();
        $normalized = $filter->filter($normalized);
        $normalized = ucfirst($normalized);

        return $normalized;
    }
}
