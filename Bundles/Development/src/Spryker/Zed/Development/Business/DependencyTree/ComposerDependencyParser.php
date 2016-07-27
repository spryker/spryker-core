<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\Word\UnderscoreToCamelCase;

class ComposerDependencyParser
{

    /**
     * @var \Spryker\Zed\Development\Business\Composer\ComposerJsonFinder
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\Development\Business\Composer\ComposerJsonFinder $finder
     */
    public function __construct($finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param string $bundleName
     * @param array $codeDependencies
     *
     * @return array
     */
    public function getComposerDependencyComparison($bundleName, $codeDependencies)
    {
        $composerDependencies = $this->getParsedComposerDependenciesForBundle($bundleName);
        $together = array_unique(array_merge($codeDependencies, $composerDependencies));
        sort($together);

        $dependencies = [];

        foreach ($together as $bundleName) {
            $dependencies[] = [
                'code' => in_array($bundleName, $codeDependencies) ? $bundleName : '',
                'composer' => in_array($bundleName, $composerDependencies) ? $bundleName : ''
            ];
        }

        return $dependencies;
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    protected function getParsedComposerDependenciesForBundle($bundleName)
    {
        $composerJsonFiles = $this->finder->find();
        $dependencies = [];
        foreach ($composerJsonFiles as $composerJsonFile) {
            if ($this->shouldSkip($composerJsonFile, $bundleName)) {
                continue;
            }

            $content = file_get_contents($composerJsonFile);
            $content = json_decode($content, true);
            $require = isset($content['require']) ? $content['require'] : [];

            foreach ($require as $package => $version) {
                if (strpos($package, 'spryker/') !== 0) {
                    continue;
                }

                $name = substr($package, 8);
                $name = str_replace('-', '_', $name);
                $filter = new UnderscoreToCamelCase();
                $name = ucfirst($filter->filter($name));

                $dependencies[] = $name;
            }
        }

        return $dependencies;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     * @param string $bundleName
     *
     * @return bool
     */
    protected function shouldSkip(SplFileInfo $composerJsonFile, $bundleName)
    {
        $folder = $composerJsonFile->getRelativePath();
        return $folder !== $bundleName;
    }

}
