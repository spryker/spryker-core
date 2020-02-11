<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleanerInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGeneratorInterface;
use Spryker\Zed\Search\Business\Model\SearchInstallerInterface;

/**
 * @deprecated Use `\Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\IndexMapInstaller` instead.
 */
class IndexMapInstaller implements SearchInstallerInterface
{
    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface
     */
    protected $indexDefinitionLoader;

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleanerInterface
     */
    protected $indexMapCleaner;

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGeneratorInterface
     */
    protected $indexMapGenerator;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $messenger;

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface $indexDefinitionLoader
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleanerInterface $indexMapCleaner
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGeneratorInterface $indexMapGenerator
     * @param \Psr\Log\LoggerInterface $messenger
     */
    public function __construct(
        IndexDefinitionLoaderInterface $indexDefinitionLoader,
        IndexMapCleanerInterface $indexMapCleaner,
        IndexMapGeneratorInterface $indexMapGenerator,
        LoggerInterface $messenger
    ) {
        $this->indexDefinitionLoader = $indexDefinitionLoader;
        $this->indexMapCleaner = $indexMapCleaner;
        $this->indexMapGenerator = $indexMapGenerator;
        $this->messenger = $messenger;
    }

    /**
     * @return void
     */
    public function install()
    {
        $this->indexMapCleaner->cleanDirectory();

        $indexDefinitions = $this->indexDefinitionLoader->loadIndexDefinitions();
        foreach ($indexDefinitions as $indexDefinition) {
            $this->messenger->info(sprintf(
                'Generating index map classes for index: "%s"',
                $indexDefinition->getIndexName()
            ));

            $this->indexMapGenerator->generate($indexDefinition);
        }
    }
}
