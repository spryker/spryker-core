<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Definition\Loader;

use Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface;
use Symfony\Component\Finder\SplFileInfo;

class IndexDefinitionLoader implements IndexDefinitionLoaderInterface
{
    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinderInterface
     */
    protected $schemaDefinitionFinder;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface
     */
    protected $indexDefinitionReader;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinderInterface $schemaDefinitionFinder
     * @param \Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface $indexDefinitionReader
     */
    public function __construct(SchemaDefinitionFinderInterface $schemaDefinitionFinder, IndexDefinitionReaderInterface $indexDefinitionReader)
    {
        $this->schemaDefinitionFinder = $schemaDefinitionFinder;
        $this->indexDefinitionReader = $indexDefinitionReader;
    }

    /**
     * @return array
     */
    public function load(): array
    {
        $indexDefinitions = [];
        foreach ($this->schemaDefinitionFinder->find() as $schemaDefinitionFile) {
            $indexName = $this->getIndexNameFromFile($schemaDefinitionFile);

            $indexDefinition = [
                'name' => $indexName,
                'definition' => $this->indexDefinitionReader->read($schemaDefinitionFile),
            ];

            $indexDefinitions[] = $indexDefinition;
        }

        return $indexDefinitions;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $indexDefinitionJsonFile
     *
     * @return string
     */
    protected function getIndexNameFromFile(SplFileInfo $indexDefinitionJsonFile): string
    {
        $fileName = $indexDefinitionJsonFile->getFilename();
        $fileExtension = $indexDefinitionJsonFile->getExtension();

        $indexName = substr($fileName, 0, -strlen($fileExtension) - 1);

        return $indexName;
    }
}
