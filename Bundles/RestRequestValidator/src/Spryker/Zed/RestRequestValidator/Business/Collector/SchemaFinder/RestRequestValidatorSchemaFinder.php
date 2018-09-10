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
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface
     */
    public function findSchemas(StoreTransfer $storeTransfer): RestRequestValidatorToFinderAdapterInterface
    {
        $this->finder
            ->in($this->getPaths($storeTransfer))
            ->name($this->config->getValidationSchemaFileNamePattern());

        return $this->finder;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string[]
     */
    protected function getPaths(StoreTransfer $storeTransfer): array
    {
        $paths = [];
        foreach ($this->config->getValidationSchemaPathPattern() as $pathPattern) {
            $pathPattern = $this->preparePathPattern($storeTransfer, $pathPattern);
            $paths += array_merge($paths, glob($pathPattern));
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
        if ($this->isProjectLevelPath($pathPattern)) {
            $pathPattern = $this->excludeStoreCodeFromPath($pathPattern);
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
    protected function excludeStoreCodeFromPath(string $pathPattern): string
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
}
