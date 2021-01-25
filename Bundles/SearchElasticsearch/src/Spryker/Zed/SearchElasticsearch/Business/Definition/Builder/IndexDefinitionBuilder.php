<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Definition\Builder;

use Generated\Shared\Transfer\IndexDefinitionTransfer;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoaderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Merger\IndexDefinitionMergerInterface;

class IndexDefinitionBuilder implements IndexDefinitionBuilderInterface
{
    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoaderInterface
     */
    protected $indexDefinitionLoader;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Definition\Merger\IndexDefinitionMergerInterface
     */
    protected $indexDefinitionMerger;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoaderInterface $indexDefinitionLoader
     * @param \Spryker\Zed\SearchElasticsearch\Business\Definition\Merger\IndexDefinitionMergerInterface $indexDefinitionMerger
     */
    public function __construct(
        IndexDefinitionLoaderInterface $indexDefinitionLoader,
        IndexDefinitionMergerInterface $indexDefinitionMerger
    ) {
        $this->indexDefinitionLoader = $indexDefinitionLoader;
        $this->indexDefinitionMerger = $indexDefinitionMerger;
    }

    /**
     * @return \Generated\Shared\Transfer\IndexDefinitionTransfer[]
     */
    public function build(): array
    {
        $indexDefinitions = [];
        foreach ($this->indexDefinitionLoader->load() as $indexDefinition) {
            $indexDefinitions = $this->mergeAndAddIndexDefinition($indexDefinitions, $indexDefinition);
        }

        return $this->buildIndexDefinitionTransferCollection($indexDefinitions);
    }

    /**
     * @param array $indexDefinitions
     * @param array $indexDefinition
     *
     * @return array
     */
    protected function mergeAndAddIndexDefinition(array $indexDefinitions, array $indexDefinition): array
    {
        $indexName = $indexDefinition['name'];
        $indexDefinition = $indexDefinition['definition'];

        if (isset($indexDefinitions[$indexName])) {
            $indexDefinition = $this->indexDefinitionMerger->merge(
                $indexDefinitions[$indexName],
                $indexDefinition
            );
        }

        $indexDefinitions[$indexName] = $indexDefinition;

        return $indexDefinitions;
    }

    /**
     * @param array $indexDefinitions
     *
     * @return \Generated\Shared\Transfer\IndexDefinitionTransfer[]
     */
    protected function buildIndexDefinitionTransferCollection(array $indexDefinitions): array
    {
        $indexDefinitionTransferCollection = [];
        foreach ($indexDefinitions as $indexName => $indexDefinition) {
            $indexDefinitionTransferCollection[] = $this->buildIndexDefinitionTransfer($indexName, $indexDefinition);
        }

        return $indexDefinitionTransferCollection;
    }

    /**
     * @param string $indexName
     * @param array $indexDefinition
     *
     * @return \Generated\Shared\Transfer\IndexDefinitionTransfer
     */
    protected function buildIndexDefinitionTransfer(string $indexName, array $indexDefinition): IndexDefinitionTransfer
    {
        $settings = $indexDefinition['settings'] ?? [];
        $mappings = $indexDefinition['mappings'] ?? [];

        $indexDefinitionTransfer = new IndexDefinitionTransfer();
        $indexDefinitionTransfer->setIndexName($indexName)
            ->setSettings($settings)
            ->setMappings($mappings);

        return $indexDefinitionTransfer;
    }
}
