<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy;

interface GlobalThresholdDataProviderResolverInterface
{
    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @throws \Spryker\Zed\MinimumOrderValueGui\Communication\Exception\MissingGlobalThresholdFormMapperException
     *
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface
     */
    public function resolveGlobalThresholdDataProviderByStrategyKey(string $minimumOrderValueTypeKey): ThresholdStrategyDataProviderInterface;

    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @return bool
     */
    public function hasGlobalThresholdDataProviderByStrategyKey(string $minimumOrderValueTypeKey): bool;
}
