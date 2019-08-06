<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Definition\Finder;

use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig;
use Symfony\Component\Finder\Finder;

class IndexDefinitionFinder implements IndexDefinitionFinderInterface
{
    /**
     * @var \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig $config
     */
    public function __construct(SearchElasticsearchConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function find(): Finder
    {
        $finder = new Finder();
        $finder->files()->in($this->config->getJsonIndexDefinitionDirectories())->notName('search.json');

        return $finder;
    }
}
