<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleanerInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGeneratorInterface;
use Spryker\Zed\Search\Business\Model\SearchInstallerInterface;

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
     * @var \Spryker\Zed\Messenger\Business\Model\MessengerInterface
     */
    protected $messenger;

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface $indexDefinitionLoader
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleanerInterface $indexMapCleaner
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGeneratorInterface $indexMapGenerator
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     */
    public function __construct(
        IndexDefinitionLoaderInterface $indexDefinitionLoader,
        IndexMapCleanerInterface $indexMapCleaner,
        IndexMapGeneratorInterface $indexMapGenerator,
        MessengerInterface $messenger
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
