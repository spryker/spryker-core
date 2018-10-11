<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui;

use Spryker\Shared\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig as SharedMerchantRelationshipSalesOrderThresholdGuiConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\HardThresholdDataProvider;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\SoftThresholdDataProvider;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\SoftThresholdFixedFeeDataProvider;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\SoftThresholdFlexibleFeeDataProvider;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\HardThresholdFormMapper;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\SoftThresholdFixedFeeFormMapper;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\SoftThresholdFlexibleFeeFormMapper;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\SoftThresholdFormMapper;

class MerchantRelationshipSalesOrderThresholdGuiConfig extends AbstractBundleConfig
{
    public const STORE_CURRENCY_DELIMITER = ';';

    protected const STRATEGY_TYPE_TO_FORM_TYPE_MAP = [
        SharedMerchantRelationshipSalesOrderThresholdGuiConfig::HARD_TYPE_STRATEGY => HardThresholdFormMapper::class,
        SharedMerchantRelationshipSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE => SoftThresholdFormMapper::class,
        SharedMerchantRelationshipSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE => SoftThresholdFlexibleFeeFormMapper::class,
        SharedMerchantRelationshipSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FIXED => SoftThresholdFixedFeeFormMapper::class,
    ];

    protected const STRATEGY_TYPE_TO_DATA_PROVIDER_MAP = [
        SharedMerchantRelationshipSalesOrderThresholdGuiConfig::HARD_TYPE_STRATEGY => HardThresholdDataProvider::class,
        SharedMerchantRelationshipSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE => SoftThresholdDataProvider::class,
        SharedMerchantRelationshipSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE => SoftThresholdFlexibleFeeDataProvider::class,
        SharedMerchantRelationshipSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FIXED => SoftThresholdFixedFeeDataProvider::class,
    ];

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdFormMapperInterface[]
     */
    public function getStrategyTypeToFormTypeMap(): array
    {
        return static::STRATEGY_TYPE_TO_FORM_TYPE_MAP;
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdFormMapperInterface[]
     */
    public function getStrategyTypeToDataProviderMap(): array
    {
        return static::STRATEGY_TYPE_TO_DATA_PROVIDER_MAP;
    }
}
