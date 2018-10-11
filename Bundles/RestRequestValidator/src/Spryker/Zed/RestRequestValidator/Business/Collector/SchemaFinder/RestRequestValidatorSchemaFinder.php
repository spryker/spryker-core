<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface;
use Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeInterface;
use Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig;

class RestRequestValidatorSchemaFinder implements RestRequestValidatorSchemaFinderInterface
{
    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface $finder
     * @param \Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig $config
     */
    public function __construct(
        RestRequestValidatorToFinderAdapterInterface $finder,
        RestRequestValidatorToStoreFacadeInterface $storeFacade,
        RestRequestValidatorConfig $config
    ) {
        $this->finder = $finder;
        $this->storeFacade = $storeFacade;
        $this->config = $config;
    }

    /**
     * @param string[] $paths
     *
     * @return \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface
     */
    public function findSchemas(array $paths): RestRequestValidatorToFinderAdapterInterface
    {
        $this->finder
            ->reset()
            ->in($paths)
            ->name($this->config->getValidationSchemaFileNamePattern());

        return $this->finder;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string[]
     */
    public function getPaths(StoreTransfer $storeTransfer): array
    {
        $paths = [];
        foreach ($this->config->getValidationSchemaPathPattern() as $pathPattern) {
            $pathPattern = $this->preparePathPattern($storeTransfer, $pathPattern);
            $currentLevelPaths = $this->excludeStoreModules($pathPattern, glob($pathPattern));
            $paths = array_merge($paths, $currentLevelPaths);
        }

        return $paths;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string $pathPattern
     *
     * @return string
     */
    protected function preparePathPattern(StoreTransfer $storeTransfer, string $pathPattern): string
    {
        if ($this->isStoreLevelPath($pathPattern)) {
            $pathPattern = $this->replaceStoreCodeInPath($pathPattern, $storeTransfer);
        }

        return $pathPattern;
    }

    /**
     * @param string $pathPattern
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string
     */
    protected function replaceStoreCodeInPath(string $pathPattern, StoreTransfer $storeTransfer): string
    {
        return sprintf($pathPattern, $storeTransfer->getName());
    }

    /**
     * @param string $pathPattern
     *
     * @return string
     */
    protected function addStoreCodesToPath(string $pathPattern): string
    {
        $excludedStoreCodes = [];
        foreach ($this->storeFacade->getAllStores() as $store) {
            $excludedStoreCodes[] = $store->getName();
        }

        return sprintf($pathPattern, implode('|', $excludedStoreCodes));
    }

    /**
     * @param string $pathPattern
     *
     * @return bool
     */
    protected function isStoreLevelPath(string $pathPattern): bool
    {
        return $pathPattern === $this->config->getStorePathPattern();
    }

    /**
     * @param string $pathPattern
     *
     * @return bool
     */
    protected function isProjectLevelPath(string $pathPattern): bool
    {
        return $pathPattern === $this->config->getProjectPathPattern();
    }

    /**
     * @param string $pathPattern
     * @param string[] $currentLevelPaths
     *
     * @return string[]
     */
    protected function excludeStoreModules(string $pathPattern, array $currentLevelPaths): array
    {
        if ($this->isProjectLevelPath($pathPattern)) {
            $currentLevelPaths = array_filter($currentLevelPaths, function ($pathItem) {
                return !preg_match(
                    $this->addStoreCodesToPath($this->config->getStoreModulesPattern()),
                    $pathItem
                );
            });
        }

        return $currentLevelPaths;
    }
}
