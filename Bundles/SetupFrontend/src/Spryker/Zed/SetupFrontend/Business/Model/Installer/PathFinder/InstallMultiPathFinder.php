<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder;

use Symfony\Component\Finder\Finder;

class InstallMultiPathFinder implements PathFinderInterface
{
    protected const PACKAGE_JSON_FILE_PATTERN = 'package.json';
    public const SEARCH_FOLDER_DEPTH_LEVEL = '< 2';

    /**
     * @var string[]
     */
    protected $pathPatterns = [];

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param array $pathPatterns
     */
    public function __construct(Finder $finder, array $pathPatterns)
    {
        $this->finder = $finder;
        $this->pathPatterns = $pathPatterns;
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    public function find()
    {
        return $this->finder
            ->files()
            ->in($this->pathPatterns)
            ->name(self::PACKAGE_JSON_FILE_PATTERN)
            ->depth('' . self::SEARCH_FOLDER_DEPTH_LEVEL);
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
