<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy;

interface GlobalThresholdDataProviderResolverInterface
{
    /**
     * @param string $strategyKey
     *
     * @throws \Spryker\Zed\MinimumOrderValueGui\Communication\Exception\MissingGlobalThresholdFormMapperException
     *
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface
     */
    public function resolveGlobalThresholdDataProviderByStrategyKey(string $strategyKey): ThresholdStrategyDataProviderInterface;

    /**
     * @param string $strategyKey
     *
     * @return bool
     */
    public function hasGlobalThresholdDataProviderByStrategyKey(string $strategyKey): bool;
}
