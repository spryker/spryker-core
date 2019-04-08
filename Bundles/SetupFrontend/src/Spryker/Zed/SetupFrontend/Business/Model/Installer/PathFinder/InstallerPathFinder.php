<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder;

use Symfony\Component\Finder\Finder;

class InstallerPathFinder implements PathFinderInterface
{
    protected const PACKAGE_JSON_FILE_PATTERN = 'package.json';

    /**
     * @var string[]
     */
    protected $pathPatterns = [];

    /**
     * @param array $pathPatterns
     */
    public function __construct(array $pathPatterns)
    {
        $this->pathPatterns = $pathPatterns;
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    public function find()
    {
        $finder = new Finder();

        $finder->files()->in($this->pathPatterns)->name(self::PACKAGE_JSON_FILE_PATTERN)->depth('< 2');

        return $finder;
    }

    /**
     * @param string $pathPattern
     *
     * @return $this
     */
    public function addPathPattern(string $pathPattern)
    {
        $this->pathPatterns[] = $pathPattern;

        return $this;
    }
}
