<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\TwigFileFinder;

use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;

class TwigFileFinder implements TwigFileFinderInterface
{
    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $module
     *
     * @return \Symfony\Component\Finder\SplFileInfo[]|\Symfony\Component\Finder\Finder
     */
    public function findTwigFiles(string $module): Finder
    {
        $finder = new Finder();
        $finder->files()->in($this->buildTwigFilePaths($module))->name('*.twig');

        return $finder;
    }

    /**
     * @param string $module
     *
     * @return bool
     */
    public function hasModuleTwigFiles(string $module): bool
    {
        $twigFilePaths = $this->buildTwigFilePaths($module);

        return (count($twigFilePaths) > 0);
    }

    /**
     * @param string $module
     *
     * @return array
     */
    protected function buildTwigFilePaths(string $module): array
    {
        $twigFilePaths = [];
        foreach ($this->config->getTwigPathPatterns() as $pathPattern) {
            $twigFilePaths[] = sprintf($pathPattern, $module);
        }

        return array_filter($twigFilePaths, 'glob');
    }
}
