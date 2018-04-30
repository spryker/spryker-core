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
use Generated\Shared\Transfer\DependencyBundleTransfer;
use Generated\Shared\Transfer\DependencyTransfer;
use Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface;
use Spryker\Zed\Development\Business\Exception\DependencyTree\InvalidComposerJsonException;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\DashToCamelCase;
use Zend\Filter\Word\SeparatorToCamelCase;

class ComposerDependencyParser implements ComposerDependencyParserInterface
{
    const TYPE_INCLUDE = 'include';
    const TYPE_EXCLUDE = 'exclude';
    const TYPE_INCLUDE_DEV = 'include-dev';
    const TYPE_EXCLUDE_DEV = 'exclude-dev';

    /**
     * @var \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface $finder
     */
    public function __construct(ComposerJsonFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return array
     */
    public function getComposerDependencyComparison(BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer): array
    {
        $moduleDependencyCollectionTransfer = $this->getOverwrittenDependenciesForBundle($moduleDependencyCollectionTransfer);
        $composerDependencyCollectionTransfer = $this->getParsedComposerDependenciesForBundle($moduleDependencyCollectionTransfer->getBundle());

        $moduleNames = $this->getBundleDependencyNames($moduleDependencyCollectionTransfer);
        $moduleNamesInSrc = $this->getBundleDependencyNamesInSrc($moduleDependencyCollectionTransfer);
        $moduleNamesInTests = $this->getBundleDependencyNamesInTests($moduleDependencyCollectionTransfer);

        $suggestedNames = $this->getSuggested($composerDependencyCollectionTransfer);
        $requireNames = $this->getRequireNames($composerDependencyCollectionTransfer);
        $requireDevNames = $this->getRequireNames($composerDependencyCollectionTransfer, true);

        $allModuleNames = array_unique(array_merge($moduleNames, $requireNames, $requireDevNames, $suggestedNames));
        sort($allModuleNames);

        $dependencies = [];

        foreach ($allModuleNames as $moduleName) {
            if ($moduleDependencyCollectionTransfer->getBundle() === $moduleName) {
                continue;
            }
            $dependencies[] = [
                'isOptional' => $this->getIsOptional($moduleName, $moduleDependencyCollectionTransfer),
                'src' => in_array($moduleName, $moduleNamesInSrc) ? $moduleName : '',
                'tests' => in_array($moduleName, $moduleNamesInTests) ? $moduleName : '',
                'composerRequire' => in_array($moduleName, $requireNames) ? $moduleName : '',
                'composerRequireDev' => in_array($moduleName, $requireDevNames) ? $moduleName : '',
                'suggested' => in_array($moduleName, $suggestedNames) ? $moduleName : '',
                'isOwnExtensionModule' => $this->isOwnExtensionModule($moduleName, $moduleDependencyCollectionTransfer),
            ];
        }

        return $dependencies;
    }

    /**
     * @param string $moduleName
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return bool
     */
    protected function getIsOptional($moduleName, BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        $isOptional = true;
        foreach ($moduleDependencyCollectionTransfer->getDependencyBundles() as $moduleDependencyTransfer) {
            if ($moduleDependencyTransfer->getBundle() === $moduleName) {
                foreach ($moduleDependencyTransfer->getDependencies() as $dependencyTransfer) {
                    if (!$dependencyTransfer->getIsOptional() && !$dependencyTransfer->getIsInTest()) {
                        $isOptional = false;
                    }
                }
            }
        }

        return $isOptional;
    }

    /**
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getBundleDependencyNames(BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        $moduleNames = [];
        foreach ($moduleDependencyCollectionTransfer->getDependencyBundles() as $moduleDependencyTransfer) {
            $moduleNames[] = $moduleDependencyTransfer->getBundle();
        }

        return $moduleNames;
    }

    /**
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getBundleDependencyNamesInSrc(BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        $moduleNames = [];
        foreach ($moduleDependencyCollectionTransfer->getDependencyBundles() as $moduleDependencyTransfer) {
            foreach ($moduleDependencyTransfer->getDependencies() as $dependencyTransfer) {
                if (!$dependencyTransfer->getIsInTest()) {
                    $moduleNames[] = $moduleDependencyTransfer->getBundle();
                }
            }
        }

        return $moduleNames;
    }

    /**
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getBundleDependencyNamesInTests(BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        $moduleNames = [];
        foreach ($moduleDependencyCollectionTransfer->getDependencyBundles() as $dependencyBundleTransfer) {
            foreach ($dependencyBundleTransfer->getDependencies() as $dependencyTransfer) {
                if ($dependencyTransfer->getIsInTest()) {
                    $moduleNames[] = $dependencyBundleTransfer->getBundle();
                }
            }
        }

        return $moduleNames;
    }

    /**
     * If a dependency is optional it needs to be in suggest.
     * Return all module names which are marked as optional.
     *
     * @param \Generated\Shared\Transfer\ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getSuggested(ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer)
    {
        $composerModuleNames = [];
        foreach ($composerDependencyCollectionTransfer->getComposerDependencies() as $composerDependency) {
            if ($composerDependency->getName() && $composerDependency->getIsOptional()) {
                $composerModuleNames[] = $composerDependency->getName();
            }
        }

        return $composerModuleNames;
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer
     * @param bool $isDev
     *
     * @return array
     */
    protected function getRequireNames(ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer, $isDev = false)
    {
        $composerModuleNames = [];
        foreach ($composerDependencyCollectionTransfer->getComposerDependencies() as $composerDependency) {
            if ($composerDependency->getName() && $composerDependency->getIsDev() === $isDev) {
                $composerModuleNames[] = $composerDependency->getName();
            }
        }

        return $composerModuleNames;
    }

    /**
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\BundleDependencyCollectionTransfer
     */
    protected function getOverwrittenDependenciesForBundle(BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        $declaredDependencies = $this->parseDeclaredDependenciesForBundle($moduleDependencyCollectionTransfer->getBundle());
        if (!$declaredDependencies) {
            return $moduleDependencyCollectionTransfer;
        }

        $excluded = array_merge($declaredDependencies[static::TYPE_EXCLUDE], $declaredDependencies[static::TYPE_EXCLUDE_DEV]);

        $dependencyModulesCollectionTransfer = $moduleDependencyCollectionTransfer->getDependencyBundles();

        $moduleDependencyCollectionTransfer->setDependencyBundles(new ArrayObject());
        foreach ($dependencyModulesCollectionTransfer as $moduleDependencyTransfer) {
            if (!in_array($moduleDependencyTransfer->getBundle(), $excluded)) {
                $moduleDependencyCollectionTransfer->addDependencyBundle($moduleDependencyTransfer);
            }
        }
        foreach ($declaredDependencies[static::TYPE_INCLUDE] as $declaredDependency) {
            $moduleDependencyCollectionTransfer = $this->addDeclaredDependency($moduleDependencyCollectionTransfer, $declaredDependency);
        }
        foreach ($declaredDependencies[static::TYPE_INCLUDE_DEV] as $declaredDependency) {
            $moduleDependencyCollectionTransfer = $this->addDeclaredDependency($moduleDependencyCollectionTransfer, $declaredDependency, true);
        }

        return $moduleDependencyCollectionTransfer;
    }

    /**
     * @param string $moduleName
     *
     * @return array
     */
    protected function parseDeclaredDependenciesForBundle($moduleName)
    {
        $composerJsonFiles = $this->finder->findAll();
        foreach ($composerJsonFiles as $composerJsonFile) {
            if ($this->shouldSkip($composerJsonFile, $moduleName)) {
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
     * @param string $moduleName
     *
     * @throws \Spryker\Zed\Development\Business\Exception\DependencyTree\InvalidComposerJsonException
     *
     * @return \Generated\Shared\Transfer\ComposerDependencyCollectionTransfer
     */
    protected function getParsedComposerDependenciesForBundle($moduleName)
    {
        $composerJsonFiles = $this->finder->findAll();

        $composerDependencies = new ComposerDependencyCollectionTransfer();

        foreach ($composerJsonFiles as $composerJsonFile) {
            if ($this->shouldSkip($composerJsonFile, $moduleName)) {
                continue;
            }

            $content = file_get_contents($composerJsonFile);
            $content = json_decode($content, true);

            if (json_last_error()) {
                throw new InvalidComposerJsonException(sprintf(
                    'Unable to parse %s: %s.',
                    $composerJsonFile,
                    json_last_error_msg()
                ));
            }

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
            if (strpos($package, 'spryker') !== 0) {
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
            if (strpos($package, 'spryker') !== 0) {
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
     * @param string $moduleName
     *
     * @return bool
     */
    protected function shouldSkip(SplFileInfo $composerJsonFile, $moduleName)
    {
        $folder = $composerJsonFile->getRelativePath();
        $filterChain = new FilterChain();
        $filterChain->attach(new DashToCamelCase());

        return ($filterChain->filter($folder) !== $moduleName);
    }

    /**
     * @param string $package
     *
     * @return string
     */
    protected function getBundleName($package)
    {
        $name = substr($package, strpos($package, '/') + 1);
        $filter = new SeparatorToCamelCase('-');
        $name = ucfirst($filter->filter($name));

        return $name;
    }

    /**
     * @param string $moduleName
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return bool
     */
    protected function isOwnExtensionModule($moduleName, $moduleDependencyCollectionTransfer)
    {
        return $moduleName === $moduleDependencyCollectionTransfer->getBundle() . 'Extension';
    }

    /**
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer
     * @param string $declaredDependency
     * @param bool $isInTest
     * @return \Generated\Shared\Transfer\BundleDependencyCollectionTransfer
     */
    protected function addDeclaredDependency(BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer, $declaredDependency, $isInTest = false)
    {
        $moduleDependencyTransfer = new DependencyBundleTransfer();
        $dependencyTransfer = new DependencyTransfer();
        $dependencyTransfer->setBundle($declaredDependency);
        $dependencyTransfer->setIsInTest($isInTest);
        $moduleDependencyTransfer->addDependency($dependencyTransfer);
        $moduleDependencyTransfer->setBundle($declaredDependency);
        $moduleDependencyCollectionTransfer->addDependencyBundle($moduleDependencyTransfer);

        return $moduleDependencyCollectionTransfer;
    }
}
