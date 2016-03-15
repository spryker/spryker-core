<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleaner;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGenerator;
use Spryker\Zed\Search\Business\Model\SearchInstallerInterface;

class IndexMapInstaller implements SearchInstallerInterface
{

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface
     */
    protected $indexDefinitionLoader;

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleaner
     */
    protected $indexMapCleaner;

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGenerator
     */
    protected $indexMapGenerator;

    /**
     * @var \Spryker\Zed\Messenger\Business\Model\MessengerInterface
     */
    protected $messenger;

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface $indexDefinitionLoader
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleaner $indexMapCleaner
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGenerator $indexMapGenerator
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     */
    public function __construct(
        IndexDefinitionLoaderInterface $indexDefinitionLoader,
        IndexMapCleaner $indexMapCleaner,
        IndexMapGenerator $indexMapGenerator,
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
            $this->indexMapGenerator->generate($indexDefinition);
        }
    }

}
