<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper;

use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Exception\MissingThresholdFormMapperException;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig;

class ThresholdMapperResolver implements ThresholdMapperResolverInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider
     */
    protected $localeProvider;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface
     */
    protected $storeCurrencyFinder;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider $localeProvider
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface $storeCurrencyFinder
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig $config
     */
    public function __construct(LocaleProvider $localeProvider, StoreCurrencyFinderInterface $storeCurrencyFinder, MerchantRelationshipMinimumOrderValueGuiConfig $config)
    {
        $this->localeProvider = $localeProvider;
        $this->storeCurrencyFinder = $storeCurrencyFinder;
        $this->config = $config;
    }

    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @throws \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Exception\MissingThresholdFormMapperException
     *
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\ThresholdFormMapperInterface
     */
    public function resolveThresholdMapperByStrategyKey(string $minimumOrderValueTypeKey): ThresholdFormMapperInterface
    {
        if (!$this->hasThresholdMapperByStrategyKey($minimumOrderValueTypeKey)) {
            throw new MissingThresholdFormMapperException();
        }
        /** @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\ThresholdFormMapperInterface $mapperClass */
        $mapperClass = $this->config->getStrategyTypeToFormTypeMap()[$minimumOrderValueTypeKey];

        return new $mapperClass($this->localeProvider, $this->storeCurrencyFinder);
    }

    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @return bool
     */
    public function hasThresholdMapperByStrategyKey(string $minimumOrderValueTypeKey): bool
    {
        return array_key_exists($minimumOrderValueTypeKey, $this->config->getStrategyTypeToFormTypeMap());
    }
}
