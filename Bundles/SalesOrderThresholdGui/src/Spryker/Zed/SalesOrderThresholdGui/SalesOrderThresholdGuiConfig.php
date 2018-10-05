<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui;

use Spryker\Shared\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig as SharedSalesOrderThresholdGuiConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\HardThresholdDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\SoftThresholdDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\SoftThresholdFixedFeeDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\SoftThresholdFlexibleFeeDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalHardThresholdFormMapper;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalSoftThresholdFixedFeeFormMapper;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalSoftThresholdFlexibleFeeFormMapper;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalSoftThresholdFormMapper;

class SalesOrderThresholdGuiConfig extends AbstractBundleConfig
{
    public const STORE_CURRENCY_DELIMITER = ';';

    protected const STRATEGY_TYPE_TO_FORM_TYPE_MAP = [
        SharedSalesOrderThresholdGuiConfig::HARD_TYPE_STRATEGY => GlobalHardThresholdFormMapper::class,
        SharedSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE => GlobalSoftThresholdFormMapper::class,
        SharedSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE => GlobalSoftThresholdFlexibleFeeFormMapper::class,
        SharedSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FIXED => GlobalSoftThresholdFixedFeeFormMapper::class,
    ];

    protected const STRATEGY_TYPE_TO_DATA_PROVIDER_MAP = [
        SharedSalesOrderThresholdGuiConfig::HARD_TYPE_STRATEGY => HardThresholdDataProvider::class,
        SharedSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE => SoftThresholdDataProvider::class,
        SharedSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE => SoftThresholdFlexibleFeeDataProvider::class,
        SharedSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FIXED => SoftThresholdFixedFeeDataProvider::class,
    ];

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface[]
     */
    public function getStrategyTypeToFormTypeMap(): array
    {
        return static::STRATEGY_TYPE_TO_FORM_TYPE_MAP;
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface[]
     */
    public function getStrategyTypeToDataProviderMap(): array
    {
        return static::STRATEGY_TYPE_TO_DATA_PROVIDER_MAP;
    }
}
