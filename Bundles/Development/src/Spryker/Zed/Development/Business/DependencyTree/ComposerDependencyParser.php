<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use ArrayObject;
use Generated\Shared\Transfer\ComposerDependencyCollectionTransfer;
use Generated\Shared\Transfer\ComposerDependencyTransfer;
use Generated\Shared\Transfer\DependencyCollectionTransfer;
use Generated\Shared\Transfer\DependencyModuleTransfer;
use Generated\Shared\Transfer\DependencyTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Development\Business\Exception\DependencyTree\InvalidComposerJsonException;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\DashToCamelCase;
use Zend\Filter\Word\SeparatorToCamelCase;

class ComposerDependencyParser implements ComposerDependencyParserInterface
{
    public const TYPE_INCLUDE = 'include';
    public const TYPE_EXCLUDE = 'exclude';
    public const TYPE_INCLUDE_DEV = 'include-dev';
    public const TYPE_EXCLUDE_DEV = 'exclude-dev';

    /**
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $dependencyCollectionTransfer
     *
     * @return array
     */
    public function getComposerDependencyComparison(DependencyCollectionTransfer $dependencyCollectionTransfer): array
    {
        $dependencyCollectionTransfer = $this->getOverwrittenDependenciesForBundle($dependencyCollectionTransfer);
        $composerDependencyCollectionTransfer = $this->getParsedComposerDependenciesForBundle($dependencyCollectionTransfer->getModule());

        $moduleNames = $this->getBundleDependencyNames($dependencyCollectionTransfer);
        $moduleNamesInSrc = $this->getBundleDependencyNamesInSrc($dependencyCollectionTransfer);
        $moduleNamesInTests = $this->getBundleDependencyNamesInTests($dependencyCollectionTransfer);

        $suggestedNames = $this->getSuggested($composerDependencyCollectionTransfer);
        $requireNames = $this->getRequireNames($composerDependencyCollectionTransfer);
        $requireDevNames = $this->getRequireNames($composerDependencyCollectionTransfer, true);

        $allModuleNames = array_unique(array_merge($moduleNames, $requireNames, $requireDevNames, $suggestedNames));
        sort($allModuleNames);

        $dependencies = [];

        foreach ($allModuleNames as $moduleName) {
            if ($dependencyCollectionTransfer->getModule()->getName() === $moduleName) {
                continue;
            }

            $dependencies[] = [
                'dependencyModule' => $moduleName,
                'types' => $this->getDependencyTypes($moduleName, $dependencyCollectionTransfer),
                'isOptional' => $this->getIsOptional($moduleName, $dependencyCollectionTransfer),
                'src' => in_array($moduleName, $moduleNamesInSrc) ? $moduleName : '',
                'tests' => in_array($moduleName, $moduleNamesInTests) ? $moduleName : '',
                'composerRequire' => in_array($moduleName, $requireNames) ? $moduleName : '',
                'composerRequireDev' => in_array($moduleName, $requireDevNames) ? $moduleName : '',
                'suggested' => in_array($moduleName, $suggestedNames) ? $moduleName : '',
                'isOwnExtensionModule' => $this->isOwnExtensionModule($moduleName, $dependencyCollectionTransfer),
            ];
        }

        return $dependencies;
    }

    /**
     * @param string $moduleName
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return bool
     */
    protected function getIsOptional($moduleName, DependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        $isOptional = true;
        $isInTestsOnly = true;
        foreach ($moduleDependencyCollectionTransfer->getDependencyModules() as $moduleDependencyTransfer) {
            if ($moduleDependencyTransfer->getModule() === $moduleName) {
                foreach ($moduleDependencyTransfer->getDependencies() as $dependencyTransfer) {
                    if (!$dependencyTransfer->getIsInTest()) {
                        $isInTestsOnly = false;
                    }
                    if (!$dependencyTransfer->getIsOptional() && !$dependencyTransfer->getIsInTest()) {
                        $isOptional = false;
                    }
                }
            }
        }

        return $isOptional && !$isInTestsOnly;
    }

    /**
     * @param string $moduleName
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return string[]
     */
    protected function getDependencyTypes($moduleName, DependencyCollectionTransfer $moduleDependencyCollectionTransfer): array
    {
        $dependencyTypes = [];
        foreach ($moduleDependencyCollectionTransfer->getDependencyModules() as $moduleDependencyTransfer) {
            if ($moduleDependencyTransfer->getModule() !== $moduleName) {
                continue;
            }

            foreach ($moduleDependencyTransfer->getDependencies() as $dependencyTransfer) {
                $dependencyTypes[$dependencyTransfer->getType()] = $dependencyTransfer->getType();
            }
        }

        return $dependencyTypes;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getBundleDependencyNames(DependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        $moduleNames = [];
        foreach ($moduleDependencyCollectionTransfer->getDependencyModules() as $moduleDependencyTransfer) {
            $moduleNames[] = $moduleDependencyTransfer->getModule();
        }

        return $moduleNames;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getBundleDependencyNamesInSrc(DependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        $moduleNames = [];
        foreach ($moduleDependencyCollectionTransfer->getDependencyModules() as $moduleDependencyTransfer) {
            foreach ($moduleDependencyTransfer->getDependencies() as $dependencyTransfer) {
                if (!$dependencyTransfer->getIsInTest()) {
                    $moduleNames[] = $moduleDependencyTransfer->getModule();
                }
            }
        }

        return $moduleNames;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getBundleDependencyNamesInTests(DependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        $moduleNames = [];
        foreach ($moduleDependencyCollectionTransfer->getDependencyModules() as $dependencyBundleTransfer) {
            foreach ($dependencyBundleTransfer->getDependencies() as $dependencyTransfer) {
                if ($dependencyTransfer->getIsInTest()) {
                    $moduleNames[] = $dependencyBundleTransfer->getModule();
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
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $dependencyCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    protected function getOverwrittenDependenciesForBundle(DependencyCollectionTransfer $dependencyCollectionTransfer)
    {
        $declaredDependencies = $this->parseDeclaredDependenciesForBundle($dependencyCollectionTransfer->getModule());
        if (!$declaredDependencies) {
            return $dependencyCollectionTransfer;
        }

        $excluded = array_merge($declaredDependencies[static::TYPE_EXCLUDE], $declaredDependencies[static::TYPE_EXCLUDE_DEV]);

        $dependencyModulesCollectionTransfer = $dependencyCollectionTransfer->getDependencyModules();

        $dependencyCollectionTransfer->setDependencyModules(new ArrayObject());
        foreach ($dependencyModulesCollectionTransfer as $moduleDependencyTransfer) {
            if (!in_array($moduleDependencyTransfer->getModule(), $excluded)) {
                $dependencyCollectionTransfer->addDependencyModule($moduleDependencyTransfer);
            }
        }
        foreach ($declaredDependencies[static::TYPE_INCLUDE] as $declaredDependency) {
            $dependencyCollectionTransfer = $this->addDeclaredDependency($dependencyCollectionTransfer, $declaredDependency);
        }
        foreach ($declaredDependencies[static::TYPE_INCLUDE_DEV] as $declaredDependency) {
            $dependencyCollectionTransfer = $this->addDeclaredDependency($dependencyCollectionTransfer, $declaredDependency, true);
        }

        return $dependencyCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @throws \Spryker\Zed\Development\Business\Exception\DependencyTree\InvalidComposerJsonException
     *
     * @return array
     */
    protected function parseDeclaredDependenciesForBundle(ModuleTransfer $moduleTransfer): array
    {
        $dependencyJsonFilePath = sprintf('%s/dependency.json', $moduleTransfer->getPath());

        if (!file_exists($dependencyJsonFilePath)) {
            return [];
        }

        $content = file_get_contents($dependencyJsonFilePath);
        $content = json_decode($content, true);

        if (json_last_error()) {
            throw new InvalidComposerJsonException(sprintf(
                'Unable to parse %s: %s.',
                $dependencyJsonFilePath,
                json_last_error_msg()
            ));
        }

        return [
            static::TYPE_INCLUDE => isset($content[static::TYPE_INCLUDE]) ? array_keys($content[static::TYPE_INCLUDE]) : [],
            static::TYPE_EXCLUDE => isset($content[static::TYPE_EXCLUDE]) ? array_keys($content[static::TYPE_EXCLUDE]) : [],
            static::TYPE_INCLUDE_DEV => isset($content[static::TYPE_INCLUDE_DEV]) ? array_keys($content[static::TYPE_INCLUDE_DEV]) : [],
            static::TYPE_EXCLUDE_DEV => isset($content[static::TYPE_EXCLUDE_DEV]) ? array_keys($content[static::TYPE_EXCLUDE_DEV]) : [],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @throws \Spryker\Zed\Development\Business\Exception\DependencyTree\InvalidComposerJsonException
     *
     * @return \Generated\Shared\Transfer\ComposerDependencyCollectionTransfer
     */
    protected function getParsedComposerDependenciesForBundle(ModuleTransfer $moduleTransfer): ComposerDependencyCollectionTransfer
    {
        $composerDependencies = new ComposerDependencyCollectionTransfer();

        $composerJsonFilePath = sprintf('%s/composer.json', $moduleTransfer->getPath());

        if (!file_exists($composerJsonFilePath)) {
            return $composerDependencies;
        }

        $content = file_get_contents($composerJsonFilePath);
        $content = json_decode($content, true);

        if (json_last_error()) {
            throw new InvalidComposerJsonException(sprintf(
                'Unable to parse %s: %s.',
                $composerJsonFilePath,
                json_last_error_msg()
            ));
        }

        $require = isset($content['require']) ? $content['require'] : [];
        $this->addComposerDependencies($require, $composerDependencies);

        $requireDev = isset($content['require-dev']) ? $content['require-dev'] : [];
        $this->addComposerDependencies($requireDev, $composerDependencies, true);

        $suggested = isset($content['suggest']) ? $content['suggest'] : [];
        $this->addSuggestedDependencies($suggested, $composerDependencies);

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
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $dependencyCollectionTransfer
     *
     * @return bool
     */
    protected function isOwnExtensionModule($moduleName, $dependencyCollectionTransfer)
    {
        return $moduleName === $dependencyCollectionTransfer->getModule()->getName() . 'Extension';
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $moduleDependencyCollectionTransfer
     * @param string $declaredDependency
     * @param bool $isInTest
     *
     * @return \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    protected function addDeclaredDependency(DependencyCollectionTransfer $moduleDependencyCollectionTransfer, $declaredDependency, $isInTest = false)
    {
        $dependencyModuleTransfer = new DependencyModuleTransfer();
        $dependencyTransfer = new DependencyTransfer();
        $dependencyTransfer->setModule($declaredDependency);
        $dependencyTransfer->setIsInTest($isInTest);
        $dependencyModuleTransfer->addDependency($dependencyTransfer);
        $dependencyModuleTransfer->setModule($declaredDependency);
        $moduleDependencyCollectionTransfer->addDependencyModule($dependencyModuleTransfer);

        return $moduleDependencyCollectionTransfer;
    }
}
