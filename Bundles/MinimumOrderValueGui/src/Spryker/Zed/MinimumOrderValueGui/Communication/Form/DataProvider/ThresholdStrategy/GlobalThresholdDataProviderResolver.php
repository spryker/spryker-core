<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy;

use Spryker\Zed\MinimumOrderValueGui\Communication\Exception\MissingThresholdDataProviderException;
use Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiConfig;

class GlobalThresholdDataProviderResolver implements GlobalThresholdDataProviderResolverInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiConfig $config
     */
    public function __construct(MinimumOrderValueGuiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $strategyKey
     *
     * @throws \Spryker\Zed\MinimumOrderValueGui\Communication\Exception\MissingThresholdDataProviderException
     *
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface
     */
    public function resolveGlobalThresholdDataProviderByStrategyKey(string $strategyKey): ThresholdStrategyDataProviderInterface
    {
        if (!$this->hasGlobalThresholdDataProviderByStrategyKey($strategyKey)) {
            throw new MissingThresholdDataProviderException();
        }
        /** @var \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface $dataProvider */
        $dataProvider = $this->config->getGlobalThresholdDataProviders()[$strategyKey];

        return new $dataProvider();
    }

    /**
     * @param string $strategyKey
     *
     * @return bool
     */
    public function hasGlobalThresholdDataProviderByStrategyKey(string $strategyKey): bool
    {
        return array_key_exists($strategyKey, $this->config->getGlobalThresholdDataProviders());
    }
}
