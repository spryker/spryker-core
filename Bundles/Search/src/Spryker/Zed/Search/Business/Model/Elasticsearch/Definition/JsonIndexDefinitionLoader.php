<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Definition;

use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Spryker\Shared\Library\Json;
use Spryker\Zed\Search\Business\Exception\InvalidIndexDefinitionException;
use Symfony\Component\Finder\Finder;

class JsonIndexDefinitionLoader implements IndexDefinitionLoaderInterface
{

    const FILE_EXTENSION = '.json';

    /**
     * @var array
     */
    protected $sourceDirectories;

    /**
     * @param array $sourceDirectories
     */
    public function __construct(array $sourceDirectories)
    {
        $this->sourceDirectories = $sourceDirectories;
    }

    /**
     * @return \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer[]
     */
    public function loadIndexDefinitions()
    {
        $indexDefinitions = [];
        $jsonFiles = $this->getJsonFiles();
        foreach ($jsonFiles as $jsonFile) {
            $indexName = substr($jsonFile->getFilename(), 0, -strlen(self::FILE_EXTENSION));
            $definitionData = Json::decode($jsonFile->getContents(), true);

            $indexDefinitions[] = $this->createIndexDefinition($indexName, $definitionData);
        }

        return $indexDefinitions;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function getJsonFiles()
    {
        $finder = new Finder();
        $finder->in($this->sourceDirectories)->name('*' . self::FILE_EXTENSION);

        return $finder;
    }

    /**
     * @param string $indexName
     * @param array $definitionData
     *
     * @return \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer
     */
    protected function createIndexDefinition($indexName, array $definitionData)
    {
        $this->assertArrayExists($indexName, $definitionData, 'settings');

        $mappings = isset($definitionData['mappings']) ? $definitionData['mappings'] : [];

        $indexDefinitionTransfer = new ElasticsearchIndexDefinitionTransfer();
        $indexDefinitionTransfer
            ->setIndexName($indexName)
            ->setSettings($definitionData['settings'])
            ->setMappings($mappings);

        return $indexDefinitionTransfer;
    }

    /**
     * @param string $indexName
     * @param array $definitionData
     * @param string $key
     *
     * @throws \Spryker\Zed\Search\Business\Exception\InvalidIndexDefinitionException
     *
     * @return void
     */
    protected function assertArrayExists($indexName, array $definitionData, $key)
    {
        if (!isset($definitionData[$key]) || !is_array($definitionData[$key])) {
            throw new InvalidIndexDefinitionException(sprintf(
                'Missing or invalid array "%s" in "%s" index definition!',
                $key,
                $indexName
            ));
        }
    }

}
