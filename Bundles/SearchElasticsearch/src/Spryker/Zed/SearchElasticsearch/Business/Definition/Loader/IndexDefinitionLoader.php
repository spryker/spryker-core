<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Definition\Loader;

use Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface;
use Spryker\Zed\SearchElasticsearch\Business\SourceIdentifier\SourceIdentifierInterface;
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
     * @var \Spryker\Zed\SearchElasticsearch\Business\SourceIdentifier\SourceIdentifierInterface
     */
    protected $sourceIdentifier;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinderInterface $schemaDefinitionFinder
     * @param \Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface $indexDefinitionReader
     * @param \Spryker\Zed\SearchElasticsearch\Business\SourceIdentifier\SourceIdentifierInterface $sourceIdentifier
     */
    public function __construct(
        SchemaDefinitionFinderInterface $schemaDefinitionFinder,
        IndexDefinitionReaderInterface $indexDefinitionReader,
        SourceIdentifierInterface $sourceIdentifier
    ) {
        $this->schemaDefinitionFinder = $schemaDefinitionFinder;
        $this->indexDefinitionReader = $indexDefinitionReader;
        $this->sourceIdentifier = $sourceIdentifier;
    }

    /**
     * @return array
     */
    public function load(): array
    {
        $indexDefinitions = [];
        $storePrefixedIndexDefinitions = [];

        foreach ($this->schemaDefinitionFinder->find() as $schemaDefinitionFile) {
            $sourceIdentifier = $this->getSourceIdentifierFromFile($schemaDefinitionFile);

            if (!$this->sourceIdentifier->isSupported($sourceIdentifier)) {
                continue;
            }

            $indexDefinition = $this->buildIndexDefinition(
                $sourceIdentifier,
                $schemaDefinitionFile
            );

            if ($this->sourceIdentifier->isPrefixedWithStoreName($sourceIdentifier)) {
                 $storePrefixedIndexDefinitions[] = $indexDefinition;

                continue;
            }

            $indexDefinitions[] = $indexDefinition;
        }

        return array_merge($indexDefinitions, $storePrefixedIndexDefinitions);
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $indexDefinitionJsonFile
     *
     * @return string
     */
    protected function getSourceIdentifierFromFile(SplFileInfo $indexDefinitionJsonFile): string
    {
        $fileName = $indexDefinitionJsonFile->getFilename();
        $fileExtension = $indexDefinitionJsonFile->getExtension();

        return substr($fileName, 0, -strlen($fileExtension) - 1);
    }

    /**
     * @param string $sourceIdentifier
     * @param \Symfony\Component\Finder\SplFileInfo $schemaDefinitionFile
     *
     * @return array
     */
    protected function buildIndexDefinition(string $sourceIdentifier, SplFileInfo $schemaDefinitionFile): array
    {
        return [
            'name' => $this->sourceIdentifier->translateToIndexName($sourceIdentifier),
            'definition' => $this->indexDefinitionReader->read($schemaDefinitionFile),
        ];
    }
}
