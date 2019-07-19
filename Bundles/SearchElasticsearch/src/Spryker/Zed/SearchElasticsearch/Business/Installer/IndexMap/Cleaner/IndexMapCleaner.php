<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Cleaner;

use Spryker\Zed\SearchElasticsearch\SearchConfig;
use Symfony\Component\Filesystem\Filesystem;

class IndexMapCleaner implements IndexMapCleanerInterface
{
    /**
     * @var \Spryker\Zed\SearchElasticsearch\SearchConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\SearchConfig $config
     */
    public function __construct(SearchConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function cleanDirectory(): void
    {
        if (is_dir($this->config->getClassTargetDirectory())) {
            $fileSystem = new Filesystem();
            $fileSystem->remove($this->config->getClassTargetDirectory());
        }
    }
}
