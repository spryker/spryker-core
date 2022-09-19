<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\GlobalHardMaximumThresholdDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\GlobalHardThresholdDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\GlobalSoftThresholdDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalHardMaximumThresholdFormMapper;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalHardThresholdFormMapper;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalSoftThresholdFormMapper;

class SalesOrderThresholdGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType::OPTION_CURRENCY_CODE
     *
     * @var string
     */
    public const OPTION_CURRENCY_CODE = 'option-currency-code';

    /**
     * @uses \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType::OPTION_LOCALE
     *
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::GROUP_HARD
     *
     * @var string
     */
    public const GROUP_HARD = 'Hard';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::GROUP_HARD_MAX
     *
     * @var string
     */
    public const GROUP_HARD_MAX = 'Hard-Max';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::GROUP_SOFT
     *
     * @var string
     */
    public const GROUP_SOFT = 'Soft';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_HARD
     *
     * @var string
     */
    public const HARD_TYPE_STRATEGY = 'hard-minimum-threshold';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_HARD_MAXIMUM
     *
     * @var string
     */
    public const THRESHOLD_STRATEGY_KEY_HARD_MAXIMUM = 'hard-maximum-threshold';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT
     *
     * @var string
     */
    public const SOFT_TYPE_STRATEGY_MESSAGE = 'soft-minimum-threshold';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT_FIXED_FEE
     *
     * @var string
     */
    public const SOFT_TYPE_STRATEGY_FIXED = 'soft-minimum-threshold-fixed-fee';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT_FLEXIBLE_FEE
     *
     * @var string
     */
    public const SOFT_TYPE_STRATEGY_FLEXIBLE = 'soft-minimum-threshold-flexible-fee';

    /**
     * @phpstan-var non-empty-string
     *
     * @var string
     */
    public const STORE_CURRENCY_DELIMITER = ';';

    /**
     * @deprecated Will be removed in the next major.
     *
     * @var array
     */
    protected const STRATEGY_TYPE_TO_FORM_TYPE_MAP = [];

    /**
     * @phpstan-var array<class-string<\Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalThresholdFormMapperInterface>>
     *
     * @var array<string, string>
     */
    protected const STRATEGY_GROUP_TO_FORM_TYPE_MAP = [
        self::GROUP_HARD => GlobalHardThresholdFormMapper::class,
        self::GROUP_HARD_MAX => GlobalHardMaximumThresholdFormMapper::class,
        self::GROUP_SOFT => GlobalSoftThresholdFormMapper::class,
    ];

    /**
     * @deprecated Will be removed in the next major.
     *
     * @var array
     */
    protected const STRATEGY_TYPE_TO_DATA_PROVIDER_MAP = [];

    /**
     * @var array<string, string>
     */
    protected const STRATEGY_GROUP_TO_DATA_PROVIDER_MAP = [
        self::GROUP_HARD => GlobalHardThresholdDataProvider::class,
        self::GROUP_HARD_MAX => GlobalHardMaximumThresholdDataProvider::class,
        self::GROUP_SOFT => GlobalSoftThresholdDataProvider::class,
    ];

    /**
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return array<string>
     */
    public function getStrategyTypeToFormTypeMap(): array
    {
        return static::STRATEGY_TYPE_TO_FORM_TYPE_MAP;
    }

    /**
     * @api
     *
     * @phpstan-return array<class-string<\Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalThresholdFormMapperInterface>>
     *
     * @return array<string>
     */
    public function getStrategyGroupToFormTypeMap(): array
    {
        return static::STRATEGY_GROUP_TO_FORM_TYPE_MAP;
    }

    /**
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return array<string>
     */
    public function getStrategyTypeToDataProviderMap(): array
    {
        return static::STRATEGY_TYPE_TO_DATA_PROVIDER_MAP;
    }

    /**
     * @api
     *
     * @return array<string, mixed>
     */
    public function getStrategyGroupToDataProviderMap(): array
    {
        return static::STRATEGY_GROUP_TO_DATA_PROVIDER_MAP;
    }
}
