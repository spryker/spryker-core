<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use ArrayObject;
use Generated\Shared\Transfer\BundleDependencyCollectionTransfer;
use Generated\Shared\Transfer\ComposerDependencyCollectionTransfer;
use Generated\Shared\Transfer\ComposerDependencyTransfer;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\Word\SeparatorToCamelCase;

class ComposerDependencyParser
{
    const TYPE_INCLUDE = 'include';
    const TYPE_EXCLUDE = 'exclude';
    const TYPE_INCLUDE_DEV = 'include-dev';
    const TYPE_EXCLUDE_DEV = 'exclude-dev';

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
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer
     *
     * @return array
     */
    public function getComposerDependencyComparison(BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer)
    {
        $bundleDependencyCollectionTransfer = $this->getOverwrittenDependenciesForBundle($bundleDependencyCollectionTransfer);
        $composerDependencyCollectionTransfer = $this->getParsedComposerDependenciesForBundle($bundleDependencyCollectionTransfer->getBundle());

        $bundleNames = $this->getBundleDependencyNames($bundleDependencyCollectionTransfer);
        $bundleNamesInSrc = $this->getBundleDependencyNamesInSrc($bundleDependencyCollectionTransfer);
        $bundleNamesInTests = $this->getBundleDependencyNamesInTests($bundleDependencyCollectionTransfer);

        $suggestedNames = $this->getSuggested($composerDependencyCollectionTransfer);
        $requireNames = $this->getRequireNames($composerDependencyCollectionTransfer);
        $requireDevNames = $this->getRequireNames($composerDependencyCollectionTransfer, true);

        $allBundleNames = array_unique(array_merge($bundleNames, $requireNames, $requireDevNames, $suggestedNames));
        sort($allBundleNames);

        $dependencies = [];

        foreach ($allBundleNames as $bundleName) {
            if ($bundleDependencyCollectionTransfer->getBundle() === $bundleName) {
                continue;
            }
            $dependencies[] = [
                'isOptional' => $this->getIsOptional($bundleName, $bundleDependencyCollectionTransfer),
                'src' => in_array($bundleName, $bundleNamesInSrc) ? $bundleName : '',
                'tests' => in_array($bundleName, $bundleNamesInTests) ? $bundleName : '',
                'composerRequire' => in_array($bundleName, $requireNames) ? $bundleName : '',
                'composerRequireDev' => in_array($bundleName, $requireDevNames) ? $bundleName : '',
                'suggested' => in_array($bundleName, $suggestedNames) ? $bundleName : '',
            ];
        }

        return $dependencies;
    }

    /**
     * @param string $bundleName
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer
     *
     * @return bool
     */
    protected function getIsOptional($bundleName, BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer)
    {
        $isOptional = true;
        foreach ($bundleDependencyCollectionTransfer->getDependencyBundles() as $dependencyBundleTransfer) {
            if ($dependencyBundleTransfer->getBundle() === $bundleName) {
                foreach ($dependencyBundleTransfer->getDependencies() as $dependencyTransfer) {
                    if (!$dependencyTransfer->getIsOptional() && !$dependencyTransfer->getIsInTest()) {
                        $isOptional = false;
                    }
                }
            }
        }

        return $isOptional;
    }

    /**
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getBundleDependencyNames(BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer)
    {
        $bundleNames = [];
        foreach ($bundleDependencyCollectionTransfer->getDependencyBundles() as $dependencyBundleTransfer) {
            $bundleNames[] = $dependencyBundleTransfer->getBundle();
        }

        return $bundleNames;
    }

    /**
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getBundleDependencyNamesInSrc(BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer)
    {
        $bundleNames = [];
        foreach ($bundleDependencyCollectionTransfer->getDependencyBundles() as $dependencyBundleTransfer) {
            $usedInSrc = false;
            foreach ($dependencyBundleTransfer->getDependencies() as $dependencyTransfer) {
                if (!$dependencyTransfer->getIsInTest()) {
                    $usedInSrc = true;
                }
            }
            if ($usedInSrc) {
                $bundleNames[] = $dependencyBundleTransfer->getBundle();
            }
        }

        return $bundleNames;
    }

    /**
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getBundleDependencyNamesInTests(BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer)
    {
        $bundleNames = [];
        foreach ($bundleDependencyCollectionTransfer->getDependencyBundles() as $dependencyBundleTransfer) {
            $usedInTests = false;
            foreach ($dependencyBundleTransfer->getDependencies() as $dependencyTransfer) {
                if ($dependencyTransfer->getIsInTest()) {
                    $usedInTests = true;
                }
            }
            if ($usedInTests) {
                $bundleNames[] = $dependencyBundleTransfer->getBundle();
            }
        }

        return $bundleNames;
    }

    /**
     * If a dependency is optional it needs to be in suggest.
     * Return all bundle names which are marked as optional.
     *
     * @param \Generated\Shared\Transfer\ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getSuggested(ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer)
    {
        $composerBundleNames = [];
        foreach ($composerDependencyCollectionTransfer->getComposerDependencies() as $composerDependency) {
            if ($composerDependency->getName() && $composerDependency->getIsOptional()) {
                $composerBundleNames[] = $composerDependency->getName();
            }
        }

        return $composerBundleNames;
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer
     * @param bool $isDev
     *
     * @return array
     */
    protected function getRequireNames(ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer, $isDev = false)
    {
        $composerBundleNames = [];
        foreach ($composerDependencyCollectionTransfer->getComposerDependencies() as $composerDependency) {
            if ($composerDependency->getName() && $composerDependency->getIsDev() === $isDev) {
                $composerBundleNames[] = $composerDependency->getName();
            }
        }

        return $composerBundleNames;
    }

    /**
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\BundleDependencyCollectionTransfer
     */
    protected function getOverwrittenDependenciesForBundle(BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer)
    {
        $declaredDependencies = $this->parseDeclaredDependenciesForBundle($bundleDependencyCollectionTransfer->getBundle());
        if (!$declaredDependencies) {
            return $bundleDependencyCollectionTransfer;
        }

        $excluded = $declaredDependencies[static::TYPE_EXCLUDE];

        $dependencyBundlesCollectionTransfer = $bundleDependencyCollectionTransfer->getDependencyBundles();
        $bundleDependencyCollectionTransfer->setDependencyBundles(new ArrayObject());
        foreach ($dependencyBundlesCollectionTransfer as $dependencyBundleTransfer) {
            if (!in_array($dependencyBundleTransfer->getBundle(), $excluded)) {
                $bundleDependencyCollectionTransfer->addDependencyBundle($dependencyBundleTransfer);
            }
        }

        return $bundleDependencyCollectionTransfer;
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    protected function parseDeclaredDependenciesForBundle($bundleName)
    {
        $composerJsonFiles = $this->finder->find();
        foreach ($composerJsonFiles as $composerJsonFile) {
            if ($this->shouldSkip($composerJsonFile, $bundleName)) {
                continue;
            }

            $path = dirname((string)$composerJsonFile);
            $dependencyFile = $path . DIRECTORY_SEPARATOR . 'dependency.json';
            if (!file_exists($dependencyFile)) {
                return [];
            }

            $content = file_get_contents($dependencyFile);
            $content = json_decode($content, true);

            return [
                static::TYPE_INCLUDE => isset($content[static::TYPE_INCLUDE]) ? array_keys($content[static::TYPE_INCLUDE]) : [],
                static::TYPE_EXCLUDE => isset($content[static::TYPE_EXCLUDE]) ? array_keys($content[static::TYPE_EXCLUDE]) : [],
                static::TYPE_INCLUDE_DEV => isset($content[static::TYPE_INCLUDE_DEV]) ? array_keys($content[static::TYPE_INCLUDE_DEV]) : [],
                static::TYPE_EXCLUDE_DEV => isset($content[static::TYPE_EXCLUDE_DEV]) ? array_keys($content[static::TYPE_EXCLUDE_DEV]) : [],
            ];
        }

        return [];
    }

    /**
     * @param string $bundleName
     *
     * @return \Generated\Shared\Transfer\ComposerDependencyCollectionTransfer
     */
    protected function getParsedComposerDependenciesForBundle($bundleName)
    {
        $composerJsonFiles = $this->finder->find();

        $composerDependencies = new ComposerDependencyCollectionTransfer();

        foreach ($composerJsonFiles as $composerJsonFile) {
            if ($this->shouldSkip($composerJsonFile, $bundleName)) {
                continue;
            }

            $content = file_get_contents($composerJsonFile);
            $content = json_decode($content, true);

            $require = isset($content['require']) ? $content['require'] : [];
            $this->addComposerDependencies($require, $composerDependencies);

            $requireDev = isset($content['require-dev']) ? $content['require-dev'] : [];
            $this->addComposerDependencies($requireDev, $composerDependencies, true);

            $suggested = isset($content['suggest']) ? $content['suggest'] : [];
            $this->addSuggestedDependencies($suggested, $composerDependencies);
        }

        return $composerDependencies;
    }

    /**
     * @param array $require
     * @param \Generated\Shared\Transfer\ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer
     * @param bool $isDev
     *
     * @return void
     */
    protected function addComposerDependencies(array $require, ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer, $isDev = false)
    {
        foreach ($require as $package => $version) {
            if (strpos($package, 'spryker/') !== 0) {
                continue;
            }
            $bundle = $this->getBundleName($package);

            $composerDependencyTransfer = new ComposerDependencyTransfer();
            $composerDependencyTransfer
                ->setName($bundle)
                ->setIsDev($isDev);

            $composerDependencyCollectionTransfer->addComposerDependency($composerDependencyTransfer);
        }
    }

    /**
     * @param array $require
     * @param \Generated\Shared\Transfer\ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer
     *
     * @return void
     */
    protected function addSuggestedDependencies(array $require, ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer)
    {
        foreach ($require as $package => $version) {
            if (strpos($package, 'spryker/') !== 0) {
                continue;
            }
            $bundle = $this->getBundleName($package);

            $composerDependencyTransfer = new ComposerDependencyTransfer();
            $composerDependencyTransfer
                ->setName($bundle)
                ->setIsOptional(true);

            $composerDependencyCollectionTransfer->addComposerDependency($composerDependencyTransfer);
        }
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

        return ($folder !== $bundleName);
    }

    /**
     * @param string $package
     *
     * @return string
     */
    protected function getBundleName($package)
    {
        $name = substr($package, 8);
        $filter = new SeparatorToCamelCase('-');
        $name = ucfirst($filter->filter($name));

        return $name;
    }
}
