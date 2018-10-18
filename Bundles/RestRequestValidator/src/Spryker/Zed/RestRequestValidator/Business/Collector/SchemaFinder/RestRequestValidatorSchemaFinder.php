<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder;

use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface;
use Spryker\Zed\RestRequestValidator\Dependency\Store\RestRequestValidatorToStoreInterface;
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
     * @var \Spryker\Zed\RestRequestValidator\Dependency\Store\RestRequestValidatorToStoreInterface
     */
    protected $store;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface $finder
     * @param \Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig $config
     * @param \Spryker\Zed\RestRequestValidator\Dependency\Store\RestRequestValidatorToStoreInterface $store
     */
    public function __construct(
        RestRequestValidatorToFinderAdapterInterface $finder,
        RestRequestValidatorConfig $config,
        RestRequestValidatorToStoreInterface $store
    ) {
        $this->finder = $finder;
        $this->config = $config;
        $this->store = $store;
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
     * @param string $storeName
     *
     * @return string[]
     */
    public function getPaths(string $storeName): array
    {
        $paths = [];
        foreach ($this->config->getValidationSchemaPathPattern() as $pathPattern) {
            $pathPattern = $this->preparePathPattern($storeName, $pathPattern);
            $currentLevelPaths = $this->excludeStoreModules($pathPattern, glob($pathPattern));
            $paths = array_merge($paths, $currentLevelPaths);
        }

        return $paths;
    }

    /**
     * @param string $storeName
     * @param string $pathPattern
     *
     * @return string
     */
    protected function preparePathPattern(string $storeName, string $pathPattern): string
    {
        if ($this->isStoreLevelPath($pathPattern)) {
            $pathPattern = $this->replaceStoreCodeInPath($pathPattern, $storeName);
        }

        return $pathPattern;
    }

    /**
     * @param string $pathPattern
     * @param string $storeName
     *
     * @return string
     */
    protected function replaceStoreCodeInPath(string $pathPattern, string $storeName): string
    {
        return sprintf($pathPattern, $storeName);
    }

    /**
     * @param string $pathPattern
     *
     * @return string
     */
    protected function addStoreCodesToPath(string $pathPattern): string
    {
        $excludedStoreCodes = [];
        foreach ($this->store->getAllowedStores() as $storeName) {
            $excludedStoreCodes[] = $storeName;
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
