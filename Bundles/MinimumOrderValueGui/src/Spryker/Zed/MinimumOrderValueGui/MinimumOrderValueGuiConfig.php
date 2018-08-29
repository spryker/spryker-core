<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui;

use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConfig as SharedMinimumOrderValueGuiConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\HardThresholdDataProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\SoftThresholdDataProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\SoftThresholdFixedFeeDataProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\SoftThresholdFlexibleFeeDataProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalHardThresholdFormMapper;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalSoftThresholdFixedFeeFormMapper;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalSoftThresholdFlexibleFeeFormMapper;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalSoftThresholdFormMapper;

class MinimumOrderValueGuiConfig extends AbstractBundleConfig
{
    protected const MAPPERS_GLOBAL_THRESHOLD = [
        SharedMinimumOrderValueGuiConfig::HARD_TYPE_STRATEGY => GlobalHardThresholdFormMapper::class,
        SharedMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE => GlobalSoftThresholdFormMapper::class,
        SharedMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE => GlobalSoftThresholdFlexibleFeeFormMapper::class,
        SharedMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FIXED => GlobalSoftThresholdFixedFeeFormMapper::class,
    ];

    protected const DATA_PROVIDERS_GLOBAL_THRESHOLD = [
        SharedMinimumOrderValueGuiConfig::HARD_TYPE_STRATEGY => HardThresholdDataProvider::class,
        SharedMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE => SoftThresholdDataProvider::class,
        SharedMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE => SoftThresholdFlexibleFeeDataProvider::class,
        SharedMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FIXED => SoftThresholdFixedFeeDataProvider::class,
    ];

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface[]
     */
    public function getGlobalThresholdMappers(): array
    {
        return static::MAPPERS_GLOBAL_THRESHOLD;
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface[]
     */
    public function getGlobalThresholdDataProviders(): array
    {
        return static::DATA_PROVIDERS_GLOBAL_THRESHOLD;
    }
}
