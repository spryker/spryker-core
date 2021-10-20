<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Collector;

use ErrorException;
use Exception;
use Laminas\Config\Config;
use Laminas\Config\Factory;
use Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder\ZedNavigationSchemaFinderInterface;
use Spryker\Zed\ZedNavigation\Business\Resolver\MergeNavigationStrategyResolverInterface;
use Spryker\Zed\ZedNavigation\ZedNavigationConfig;

class ZedNavigationCollector implements ZedNavigationCollectorInterface
{
    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder\ZedNavigationSchemaFinderInterface
     */
    protected $navigationSchemaFinder;

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Resolver\MergeNavigationStrategyResolverInterface
     */
    protected $mergeNavigationStrategyResolver;

    /**
     * @var \Spryker\Zed\ZedNavigation\ZedNavigationConfig
     */
    protected $zedNavigationConfig;

    /**
     * @var array|null
     */
    protected $navigationDefinition;

    /**
     * @param \Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder\ZedNavigationSchemaFinderInterface $navigationSchemaFinder
     * @param \Spryker\Zed\ZedNavigation\Business\Resolver\MergeNavigationStrategyResolverInterface $mergeNavigationStrategyResolver
     * @param \Spryker\Zed\ZedNavigation\ZedNavigationConfig $zedNavigationConfig
     */
    public function __construct(
        ZedNavigationSchemaFinderInterface $navigationSchemaFinder,
        MergeNavigationStrategyResolverInterface $mergeNavigationStrategyResolver,
        ZedNavigationConfig $zedNavigationConfig
    ) {
        $this->navigationSchemaFinder = $navigationSchemaFinder;
        $this->mergeNavigationStrategyResolver = $mergeNavigationStrategyResolver;
        $this->zedNavigationConfig = $zedNavigationConfig;
    }

    /**
     * @param string $navigationType
     *
     * @throws \ErrorException
     *
     * @return array
     */
    public function getNavigation(string $navigationType): array
    {
        if ($this->navigationDefinition !== null) {
            return $this->navigationDefinition;
        }

        try {
            /** @var \Laminas\Config\Config $navigationDefinition */
            $navigationDefinition = Factory::fromFile($this->zedNavigationConfig->getRootNavigationSchemaPaths()[$navigationType], true);
            $rootDefinition = clone $navigationDefinition;
        } catch (Exception $e) {
            $navigationDefinition = new Config([]);
            $rootDefinition = new Config([]);
        }

        $coreNavigationDefinition = new Config([]);
        $fileNamePattern = $this->zedNavigationConfig->getNavigationSchemaFileNamePatterns()[$navigationType];
        foreach ($this->navigationSchemaFinder->getSchemaFiles($fileNamePattern) as $moduleNavigationFile) {
            if (!file_exists($moduleNavigationFile->getPathname())) {
                throw new ErrorException('Navigation-File does not exist: ' . $moduleNavigationFile);
            }
            /** @var \Laminas\Config\Config $configFromFile */
            $configFromFile = Factory::fromFile($moduleNavigationFile->getPathname(), true);
            $navigationDefinition->merge($configFromFile);
            $coreNavigationDefinition->merge($configFromFile);
        }

        $navigationMergeStrategy = $this->mergeNavigationStrategyResolver->resolve();

        return $navigationMergeStrategy->mergeNavigation(
            $navigationDefinition,
            $rootDefinition,
            $coreNavigationDefinition,
        );
    }
}
