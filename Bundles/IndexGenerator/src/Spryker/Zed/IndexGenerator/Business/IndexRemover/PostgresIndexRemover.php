<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Business\IndexRemover;

use Spryker\Zed\IndexGenerator\IndexGeneratorConfig;
use Symfony\Component\Finder\Finder;

class PostgresIndexRemover implements PostgresIndexRemoverInterface
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
     * @return void
     */
    public function removeIndexes(): void
    {
        $targetDirectory = $this->config->getTargetDirectory();
        if (!is_dir($targetDirectory)) {
            return;
        }

        foreach ($this->getSchemaFinder() as $schemaFileInfo) {
            unlink($schemaFileInfo->getPathname());
        }
    }

    /**
     * @return \Symfony\Component\Finder\Finder<\Symfony\Component\Finder\SplFileInfo>
     */
    protected function getSchemaFinder(): Finder
    {
        $finder = new Finder();

        return $finder->in($this->config->getTargetDirectory())->files();
    }
}
