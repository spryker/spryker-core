<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Business\SchemaFinder;

use Spryker\Zed\IndexGenerator\IndexGeneratorConfig;
use Symfony\Component\Finder\Finder;

class MergedSchemaFinder implements MergedSchemaFinderInterface
{
    /**
     * @var \Spryker\Zed\IndexGenerator\IndexGeneratorConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\IndexGenerator\IndexGeneratorConfig $config
     */
    public function __construct(IndexGeneratorConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function findMergedSchemas(): Finder
    {
        $finder = new Finder();
        $finder->files()->in($this->config->getPathToMergedSchemas());

        return $finder;
    }
}
