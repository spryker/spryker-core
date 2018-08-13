<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper;

interface GlobalThresholdMapperResolverInterface
{
    /**
     * @param string $strategyKey
     *
     * @throws \Spryker\Zed\MinimumOrderValueGui\Communication\Exception\MissingGlobalThresholdFormMapperException
     *
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface
     */
    public function resolveGlobalThresholdMapperByStrategyKey(string $strategyKey): GlobalThresholdFormMapperInterface;

    /**
     * @param string $strategyKey
     *
     * @return bool
     */
    public function hasGlobalThresholdMapperByStrategyKey(string $strategyKey): bool;
}
