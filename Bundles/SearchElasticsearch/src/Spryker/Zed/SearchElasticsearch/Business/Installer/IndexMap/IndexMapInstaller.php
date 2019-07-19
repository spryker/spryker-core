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
     * @param \Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface $indexDefinitionBuilder
     * @param \Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Cleaner\IndexMapCleanerInterface $indexMapCleaner
     * @param \Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Generator\IndexMapGeneratorInterface $indexMapGenerator
     */
    public function __construct(IndexDefinitionBuilderInterface $indexDefinitionBuilder, IndexMapCleanerInterface $indexMapCleaner, IndexMapGeneratorInterface $indexMapGenerator)
    {
        $this->indexDefinitionBuilder = $indexDefinitionBuilder;
        $this->indexMapCleaner = $indexMapCleaner;
        $this->indexMapGenerator = $indexMapGenerator;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function install(LoggerInterface $logger): void
    {
        $this->indexMapCleaner->cleanDirectory();

        foreach ($this->indexDefinitionBuilder->build() as $indexDefinitionTransfer) {
            $logger->info(sprintf('Generating index map classes for index "%s"', $indexDefinitionTransfer->getIndexName()));

            $this->indexMapGenerator->generate($indexDefinitionTransfer);
        }
    }
}
