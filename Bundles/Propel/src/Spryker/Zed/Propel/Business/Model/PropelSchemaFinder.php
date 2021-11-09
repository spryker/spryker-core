<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model;

use Symfony\Component\Finder\Finder;

class PropelSchemaFinder implements PropelSchemaFinderInterface
{
    /**
     * @var string
     */
    public const FILE_NAME_PATTERN = '*_*.schema.xml';

    /**
     * @var array<string>
     */
    protected $pathPatterns;

    /**
     * @param array<string> $pathPatterns
     */
    public function __construct(array $pathPatterns)
    {
        $this->pathPatterns = $pathPatterns;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function getSchemaFiles()
    {
        $finder = new Finder();
        $finder
            ->in($this->pathPatterns)
            ->name(static::FILE_NAME_PATTERN)
            ->depth(0);

        return $finder;
    }
}
