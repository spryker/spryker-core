<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap;

use Psr\Log\LoggerInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Cleaner\IndexMapCleanerInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Generator\IndexMapGeneratorInterface;
use Spryker\Zed\SearchElasticsearch\Dependency\Facade\SearchElasticsearchToStoreFacadeInterface;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig;

class IndexMapInstaller implements IndexMapInstallerInterface
{
    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface
     */
    protected $indexDefinitionBuilder;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Cleaner\IndexMapCleanerInterface
     */
    protected $indexMapCleaner;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Generator\IndexMapGeneratorInterface
     */
    protected $indexMapGenerator;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Dependency\Facade\SearchElasticsearchToStoreFacadeInterface
     */
    protected SearchElasticsearchToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig
     */
    protected SearchElasticsearchConfig $config;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface $indexDefinitionBuilder
     * @param \Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Cleaner\IndexMapCleanerInterface $indexMapCleaner
     * @param \Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Generator\IndexMapGeneratorInterface $indexMapGenerator
     * @param \Spryker\Zed\SearchElasticsearch\Dependency\Facade\SearchElasticsearchToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig $config
     */
    public function __construct(
        IndexDefinitionBuilderInterface $indexDefinitionBuilder,
        IndexMapCleanerInterface $indexMapCleaner,
        IndexMapGeneratorInterface $indexMapGenerator,
        SearchElasticsearchToStoreFacadeInterface $storeFacade,
        SearchElasticsearchConfig $config
    ) {
        $this->indexDefinitionBuilder = $indexDefinitionBuilder;
        $this->indexMapCleaner = $indexMapCleaner;
        $this->indexMapGenerator = $indexMapGenerator;
        $this->storeFacade = $storeFacade;
        $this->config = $config;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function install(LoggerInterface $logger): void
    {
        $this->indexMapCleaner->cleanDirectory();

        foreach ($this->getGetIndexDefinitionTransfers() as $indexDefinitionTransfer) {
            $logger->info(sprintf('Generating index map classes for index "%s"', $indexDefinitionTransfer->getIndexName()));

            $this->indexMapGenerator->generate($indexDefinitionTransfer);
        }
    }

    /**
     * @return array<\Generated\Shared\Transfer\IndexDefinitionTransfer>
     */
    protected function getGetIndexDefinitionTransfers(): array
    {
        /* Required by infrastructure, exists only for BC with DMS OFF mode. */
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $this->indexDefinitionBuilder->build($this->getCurrentStore());
        }

        $indexDefinitionTransfers = [];
        foreach ($this->config->getAllowedStoresForSourceIdentifierPrefixing() as $storeName) {
            $indexDefinitionTransfers = array_merge($indexDefinitionTransfers, $this->indexDefinitionBuilder->build($storeName));
        }

        if ($indexDefinitionTransfers) {
            return $indexDefinitionTransfers;
        }

        return $this->indexDefinitionBuilder->build(null);
    }

    /**
     * @return string
     */
    protected function getCurrentStore(): string
    {
        return APPLICATION_STORE;
    }
}
