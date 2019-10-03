<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Definition\Loader;

use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface;
use Symfony\Component\Finder\SplFileInfo;

class IndexDefinitionLoader implements IndexDefinitionLoaderInterface
{
    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinderInterface
     */
    protected $indexDefinitionFinder;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface
     */
    protected $indexDefinitionReader;

    /**
     * @var \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface
     */
    protected $indexNameResolver;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinderInterface $indexDefinitionFinder
     * @param \Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface $indexDefinitionReader
     * @param \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface $indexNameResolver
     */
    public function __construct(SchemaDefinitionFinderInterface $indexDefinitionFinder, IndexDefinitionReaderInterface $indexDefinitionReader, IndexNameResolverInterface $indexNameResolver)
    {
        $this->indexDefinitionFinder = $indexDefinitionFinder;
        $this->indexDefinitionReader = $indexDefinitionReader;
        $this->indexNameResolver = $indexNameResolver;
    }

    /**
     * @return array
     */
    public function load(): array
    {
        $indexDefinitions = [];
        foreach ($this->indexDefinitionFinder->find() as $indexDefinitionFile) {
            $indexName = $this->getIndexNameFromFile($indexDefinitionFile);

            $indexDefinition = [
                'name' => $indexName,
                'definition' => $this->indexDefinitionReader->read($indexDefinitionFile),
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
