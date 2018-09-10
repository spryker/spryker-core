<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui;

use Spryker\Shared\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig as SharedMerchantRelationshipMinimumOrderValueGuiConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\HardThresholdDataProvider;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\SoftThresholdDataProvider;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\SoftThresholdFixedFeeDataProvider;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\SoftThresholdFlexibleFeeDataProvider;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\HardThresholdFormMapper;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\SoftThresholdFixedFeeFormMapper;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\SoftThresholdFlexibleFeeFormMapper;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\SoftThresholdFormMapper;

class MerchantRelationshipMinimumOrderValueGuiConfig extends AbstractBundleConfig
{
    public const STORE_CURRENCY_DELIMITER = ';';

    protected const STRATEGY_TYPE_TO_FORM_TYPE_MAP = [
        SharedMerchantRelationshipMinimumOrderValueGuiConfig::HARD_TYPE_STRATEGY => HardThresholdFormMapper::class,
        SharedMerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE => SoftThresholdFormMapper::class,
        SharedMerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE => SoftThresholdFlexibleFeeFormMapper::class,
        SharedMerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FIXED => SoftThresholdFixedFeeFormMapper::class,
    ];

    protected const STRATEGY_TYPE_TO_DATA_PROVIDER_MAP = [
        SharedMerchantRelationshipMinimumOrderValueGuiConfig::HARD_TYPE_STRATEGY => HardThresholdDataProvider::class,
        SharedMerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE => SoftThresholdDataProvider::class,
        SharedMerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE => SoftThresholdFlexibleFeeDataProvider::class,
        SharedMerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FIXED => SoftThresholdFixedFeeDataProvider::class,
    ];

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\ThresholdFormMapperInterface[]
     */
    public function getStrategyTypeToFormTypeMap(): array
    {
        return static::STRATEGY_TYPE_TO_FORM_TYPE_MAP;
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\ThresholdFormMapperInterface[]
     */
    public function getStrategyTypeToDataProviderMap(): array
    {
        return static::STRATEGY_TYPE_TO_DATA_PROVIDER_MAP;
    }
}
