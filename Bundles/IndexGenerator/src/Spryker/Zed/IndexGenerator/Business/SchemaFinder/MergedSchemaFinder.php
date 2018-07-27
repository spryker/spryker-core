<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Business\SchemaFinder;

use Symfony\Component\Finder\Finder;

class MergedSchemaFinder implements MergedSchemaFinderInterface
{
    /**
     * @var string
     */
    protected $pathToMergedSchemas;

    /**
     * @param string $pathToMergedSchemas
     */
    public function __construct(string $pathToMergedSchemas)
    {
        $this->pathToMergedSchemas = $pathToMergedSchemas;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function findMergedSchemas(): Finder
    {
        $finder = new Finder();
        $finder->files()->in($this->pathToMergedSchemas);

        return $finder;
    }
}
