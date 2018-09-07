<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper;

use Spryker\Zed\MinimumOrderValueGui\Communication\Exception\MissingGlobalThresholdFormMapperException;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface;
use Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiConfig;

class GlobalThresholdMapperResolver implements GlobalThresholdMapperResolverInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider
     */
    protected $localeProvider;

    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface
     */
    protected $storeCurrencyFinder;

    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider $localeProvider
     * @param \Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface $storeCurrencyFinder
     * @param \Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiConfig $config
     */
    public function __construct(LocaleProvider $localeProvider, StoreCurrencyFinderInterface $storeCurrencyFinder, MinimumOrderValueGuiConfig $config)
    {
        $this->localeProvider = $localeProvider;
        $this->storeCurrencyFinder = $storeCurrencyFinder;
        $this->config = $config;
    }

    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @throws \Spryker\Zed\MinimumOrderValueGui\Communication\Exception\MissingGlobalThresholdFormMapperException
     *
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface
     */
    public function resolveGlobalThresholdMapperByStrategyKey(string $minimumOrderValueTypeKey): GlobalThresholdFormMapperInterface
    {
        if (!$this->hasGlobalThresholdMapperByStrategyKey($minimumOrderValueTypeKey)) {
            throw new MissingGlobalThresholdFormMapperException();
        }
        /** @var \Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface $mapperClass */
        $mapperClass = $this->config->getStrategyTypeToFormTypeMap()[$minimumOrderValueTypeKey];

        return new $mapperClass($this->localeProvider, $this->storeCurrencyFinder);
    }

    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @return bool
     */
    public function hasGlobalThresholdMapperByStrategyKey(string $minimumOrderValueTypeKey): bool
    {
        return array_key_exists($minimumOrderValueTypeKey, $this->config->getStrategyTypeToFormTypeMap());
    }
}
