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
use Spryker\Zed\Development\Business\Composer\ComposerNameFinderInterface;
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
     * @var \Spryker\Zed\Development\Business\Composer\ComposerNameFinderInterface
     */
    protected $composerNameFinder;

    /**
     * @param \Spryker\Zed\Development\Business\Composer\ComposerNameFinderInterface $composerNameFinder
     */
    public function __construct(ComposerNameFinderInterface $composerNameFinder)
    {
        $this->composerNameFinder = $composerNameFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $dependencyCollectionTransfer
     *
     * @return array
     */
    public function getComposerDependencyComparison(DependencyCollectionTransfer $dependencyCollectionTransfer): array
    {
        $dependencyCollectionTransfer = $this->getOverwrittenDependenciesForBundle($dependencyCollectionTransfer);
        $composerDependencyCollectionTransfer = $this->getParsedComposerDependenciesForBundle($dependencyCollectionTransfer->getModule());

        $composerNames = $this->getComposerNames($dependencyCollectionTransfer);
        $composerNamesInSrc = $this->getComposerNamesForInSrcUsedModules($dependencyCollectionTransfer);
        $composerNamesInTests = $this->getComposerNamesForInTestsUsedModules($dependencyCollectionTransfer);

        $suggestedNames = $this->getSuggested($composerDependencyCollectionTransfer);
        $requireNames = $this->getRequireNames($composerDependencyCollectionTransfer);
        $requireDevNames = $this->getRequireNames($composerDependencyCollectionTransfer, true);

        $allComposerNames = $composerNames + $requireNames + $requireDevNames + $suggestedNames;
        asort($allComposerNames);

        $dependencies = [];

        $currentComposerName = sprintf('%s/%s', $dependencyCollectionTransfer->getModule()->getOrganization()->getNameDashed(), $dependencyCollectionTransfer->getModule()->getNameDashed());

        foreach ($allComposerNames as $moduleName => $composerName) {
            if ($currentComposerName === $composerName) {
                continue;
            }

            $dependencies[] = [
                'moduleName' => $moduleName,
                'composerName' => $composerName,
                'types' => $this->getDependencyTypes($composerName, $dependencyCollectionTransfer),
                'isOptional' => $this->getIsOptional($composerName, $dependencyCollectionTransfer),
                'src' => in_array($composerName, $composerNamesInSrc) ? $composerName : '',
                'tests' => in_array($composerName, $composerNamesInTests) ? $composerName : '',
                'composerRequire' => in_array($composerName, $requireNames) ? $composerName : '',
                'composerRequireDev' => in_array($composerName, $requireDevNames) ? $composerName : '',
                'suggested' => in_array($composerName, $suggestedNames) ? $composerName : '',
                'isOwnExtensionModule' => $this->isOwnExtensionModule($composerName, $dependencyCollectionTransfer),
            ];
        }

        return $dependencies;
    }

    /**
     * @param string $composerName
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return bool
     */
    protected function getIsOptional($composerName, DependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        $isOptional = true;
        $isInTestsOnly = true;
        foreach ($moduleDependencyCollectionTransfer->getDependencyModules() as $moduleDependencyTransfer) {
            if ($moduleDependencyTransfer->getComposerName() === $composerName) {
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
     * @param string $composerName
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return string[]
     */
    protected function getDependencyTypes($composerName, DependencyCollectionTransfer $moduleDependencyCollectionTransfer): array
    {
        $dependencyTypes = [];
        foreach ($moduleDependencyCollectionTransfer->getDependencyModules() as $moduleDependencyTransfer) {
            if ($moduleDependencyTransfer->getComposerName() !== $composerName) {
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
    protected function getComposerNames(DependencyCollectionTransfer $moduleDependencyCollectionTransfer): array
    {
        $composerNames = [];
        foreach ($moduleDependencyCollectionTransfer->getDependencyModules() as $moduleDependencyTransfer) {
            $composerNames[$moduleDependencyTransfer->getModule()] = $moduleDependencyTransfer->getComposerName();
        }

        return array_unique($composerNames);
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getComposerNamesForInSrcUsedModules(DependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        $composerNames = [];
        foreach ($moduleDependencyCollectionTransfer->getDependencyModules() as $moduleDependencyTransfer) {
            foreach ($moduleDependencyTransfer->getDependencies() as $dependencyTransfer) {
                if (!$dependencyTransfer->getIsInTest()) {
                    $composerNames[$moduleDependencyTransfer->getModule()] = $moduleDependencyTransfer->getComposerName();
                }
            }
        }

        return $composerNames;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getComposerNamesForInTestsUsedModules(DependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        $composerNames = [];
        foreach ($moduleDependencyCollectionTransfer->getDependencyModules() as $moduleDependencyTransfer) {
            foreach ($moduleDependencyTransfer->getDependencies() as $dependencyTransfer) {
                if ($dependencyTransfer->getIsInTest()) {
                    $composerNames[$moduleDependencyTransfer->getModule()] = $moduleDependencyTransfer->getComposerName();
                }
            }
        }

        return $composerNames;
    }

    /**
     * If a dependency is optional it needs to be in suggest.
     * Return all composer names which are marked as optional.
     *
     * @param \Generated\Shared\Transfer\ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getSuggested(ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer)
    {
        $composerNames = [];
        foreach ($composerDependencyCollectionTransfer->getComposerDependencies() as $composerDependency) {
            if ($composerDependency->getName() && $composerDependency->getIsOptional()) {
                $composerNames[$composerDependency->getModuleName()] = $composerDependency->getName();
            }
        }

        return $composerNames;
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer
     * @param bool $isDev
     *
     * @return array
     */
    protected function getRequireNames(ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer, $isDev = false)
    {
        $composerNames = [];
        foreach ($composerDependencyCollectionTransfer->getComposerDependencies() as $composerDependency) {
            if ($composerDependency->getName() && $composerDependency->getIsDev() === $isDev) {
                $composerNames[$composerDependency->getModuleName()] = $composerDependency->getName();
            }
        }

        return $composerNames;
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
            if (!in_array($moduleDependencyTransfer->getComposerName(), $excluded)) {
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
            $module = $this->getBundleName($package);

            $composerDependencyTransfer = new ComposerDependencyTransfer();
            $composerDependencyTransfer
                ->setName($package)
                ->setModuleName($module)
                ->setIsDev($isDev);

            $composerDependencyCollectionTransfer->addComposerDependency($composerDependencyTransfer);
        }
    }

    /**
     * @param array $suggested
     * @param \Generated\Shared\Transfer\ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer
     *
     * @return void
     */
    protected function addSuggestedDependencies(array $suggested, ComposerDependencyCollectionTransfer $composerDependencyCollectionTransfer)
    {
        foreach ($suggested as $package => $description) {
            if (strpos($package, 'spryker') !== 0) {
                continue;
            }
            $module = $this->getBundleName($package);

            $composerDependencyTransfer = new ComposerDependencyTransfer();
            $composerDependencyTransfer
                ->setName($package)
                ->setModuleName($module)
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
     * @param string $composerName
     * @param bool $isInTest
     *
     * @return \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    protected function addDeclaredDependency(DependencyCollectionTransfer $moduleDependencyCollectionTransfer, string $composerName, $isInTest = false)
    {
        $moduleName = $this->getBundleName($composerName);

        $dependencyModuleTransfer = new DependencyModuleTransfer();
        $dependencyTransfer = new DependencyTransfer();
        $dependencyTransfer->setModule($moduleName);
        $dependencyTransfer->setComposerName($composerName);
        $dependencyTransfer->setIsInTest($isInTest);
        $dependencyModuleTransfer->addDependency($dependencyTransfer);
        $dependencyModuleTransfer->setModule($moduleName);
        $dependencyModuleTransfer->setComposerName($composerName);
        $moduleDependencyCollectionTransfer->addDependencyModule($dependencyModuleTransfer);

        return $moduleDependencyCollectionTransfer;
    }
}
