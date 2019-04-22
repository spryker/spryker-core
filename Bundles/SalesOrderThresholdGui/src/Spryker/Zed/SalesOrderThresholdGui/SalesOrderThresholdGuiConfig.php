<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\GlobalHardThresholdDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\GlobalSoftThresholdDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalHardThresholdFormMapper;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalSoftThresholdFormMapper;

class SalesOrderThresholdGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType::OPTION_CURRENCY_CODE
     */
    public const OPTION_CURRENCY_CODE = 'option-currency-code';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::GROUP_HARD
     */
    public const GROUP_HARD = 'Hard';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::GROUP_SOFT
     */
    public const GROUP_SOFT = 'Soft';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_HARD
     */
    public const HARD_TYPE_STRATEGY = 'hard-minimum-threshold';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT
     */
    public const SOFT_TYPE_STRATEGY_MESSAGE = 'soft-minimum-threshold';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT_FIXED_FEE
     */
    public const SOFT_TYPE_STRATEGY_FIXED = 'soft-minimum-threshold-fixed-fee';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT_FLEXIBLE_FEE
     */
    public const SOFT_TYPE_STRATEGY_FLEXIBLE = 'soft-minimum-threshold-flexible-fee';

    public const STORE_CURRENCY_DELIMITER = ';';

    /**
     * @deprecated Will be removed in the next major.
     */
    protected const STRATEGY_TYPE_TO_FORM_TYPE_MAP = [];

    protected const STRATEGY_GROUP_TO_FORM_TYPE_MAP = [
        self::GROUP_HARD => GlobalHardThresholdFormMapper::class,
        self::GROUP_SOFT => GlobalSoftThresholdFormMapper::class,
    ];

    /**
     * @deprecated Will be removed in the next major.
     */
    protected const STRATEGY_TYPE_TO_DATA_PROVIDER_MAP = [];

    protected const STRATEGY_GROUP_TO_DATA_PROVIDER_MAP = [
        self::GROUP_HARD => GlobalHardThresholdDataProvider::class,
        self::GROUP_SOFT => GlobalSoftThresholdDataProvider::class,
    ];

    /**
     * @deprecated Will be removed in the next major.
     *
     * @return array
     */
    public function getStrategyTypeToFormTypeMap(): array
    {
        return static::STRATEGY_TYPE_TO_FORM_TYPE_MAP;
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalThresholdFormMapperInterface[]
     */
    public function getStrategyGroupToFormTypeMap(): array
    {
        return static::STRATEGY_GROUP_TO_FORM_TYPE_MAP;
    }

    /**
     * @deprecated Will be removed in the next major.
     *
     * @return array
     */
    public function getStrategyTypeToDataProviderMap(): array
    {
        return static::STRATEGY_TYPE_TO_DATA_PROVIDER_MAP;
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalThresholdFormMapperInterface[]
     */
    public function getStrategyGroupToDataProviderMap(): array
    {
        return static::STRATEGY_GROUP_TO_DATA_PROVIDER_MAP;
    }
}
