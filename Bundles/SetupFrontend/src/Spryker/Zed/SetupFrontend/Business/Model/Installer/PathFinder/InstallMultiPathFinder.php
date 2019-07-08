<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder;

use Spryker\Zed\SetupFrontend\Business\Model\Exception\PathPatternInvalidException;
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
     * @var \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathPatternValidatorInterface
     */
    protected $pathPatternValidator;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param array $pathPatterns
     * @param \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathPatternValidatorInterface $pathPatternValidator
     */
    public function __construct(
        Finder $finder,
        array $pathPatterns,
        PathPatternValidatorInterface $pathPatternValidator
    ) {
        $this->finder = $finder;
        $this->pathPatterns = $pathPatterns;
        $this->pathPatternValidator = $pathPatternValidator;
    }

    /**
     * @throws \Spryker\Zed\SetupFrontend\Business\Model\Exception\PathPatternInvalidException
     *
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function find(): Finder
    {
        if (!$this->pathPatternValidator->isValidPathPatterns($this->pathPatterns)) {
            throw new PathPatternInvalidException();
        }

        return $this->finder
            ->files()
            ->in($this->pathPatterns)
            ->name(static::PACKAGE_JSON_FILE_PATTERN)
            ->depth('' . static::SEARCH_FOLDER_DEPTH_LEVEL);
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
