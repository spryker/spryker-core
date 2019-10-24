<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch;

use Codeception\Actor;
use Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchBusinessFactory;
use Zend\Filter\Word\UnderscoreToCamelCase;

/**
 * Inherited Methods
 *
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
            ->createIndexNameResolver()
            ->resolve($sourceIdentifier);
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
