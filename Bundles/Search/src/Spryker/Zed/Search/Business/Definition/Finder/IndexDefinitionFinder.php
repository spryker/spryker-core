<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Definition\Finder;

use Spryker\Zed\Search\SearchConfig;
use Symfony\Component\Finder\Finder;

class IndexDefinitionFinder implements IndexDefinitionFinderInterface
{
    /**
     * @var \Spryker\Zed\Search\SearchConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Search\SearchConfig $config
     */
    public function __construct(SearchConfig $config)
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
