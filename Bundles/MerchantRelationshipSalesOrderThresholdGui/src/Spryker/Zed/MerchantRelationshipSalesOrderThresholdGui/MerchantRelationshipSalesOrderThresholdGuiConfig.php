<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\MerchantRelationshipHardMaximumThresholdDataProvider;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\MerchantRelationshipHardThresholdDataProvider;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\MerchantRelationshipSoftThresholdDataProvider;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\MerchantRelationshipHardMaximumThresholdFormMapper;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\MerchantRelationshipHardThresholdFormMapper;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\MerchantRelationshipSoftThresholdFormMapper;

class MerchantRelationshipSalesOrderThresholdGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\MerchantRelationshipThresholdType::OPTION_CURRENCY_CODE
     * @var string
     */
    public const OPTION_CURRENCY_CODE = 'option-currency-code';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::GROUP_HARD
     * @var string
     */
    public const GROUP_HARD = 'Hard';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::GROUP_SOFT
     * @var string
     */
    public const GROUP_SOFT = 'Soft';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::GROUP_HARD_MAX
     * @var string
     */
    public const GROUP_HARD_MAX = 'Hard-Max';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_HARD
     * @var string
     */
    public const HARD_TYPE_STRATEGY = 'hard-minimum-threshold';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_HARD_MAXIMUM
     * @var string
     */
    public const THRESHOLD_STRATEGY_KEY_HARD_MAXIMUM = 'hard-maximum-threshold';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT
     * @var string
     */
    public const SOFT_TYPE_STRATEGY_MESSAGE = 'soft-minimum-threshold';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT_FIXED_FEE
     * @var string
     */
    public const SOFT_TYPE_STRATEGY_FIXED = 'soft-minimum-threshold-fixed-fee';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT_FLEXIBLE_FEE
     * @var string
     */
    public const SOFT_TYPE_STRATEGY_FLEXIBLE = 'soft-minimum-threshold-flexible-fee';

    /**
     * @phpstan-var non-empty-string
     * @var string
     */
    public const STORE_CURRENCY_DELIMITER = ';';

    /**
     * @deprecated Will be removed in the next major.
     * @var array
     */
    protected const STRATEGY_TYPE_TO_FORM_TYPE_MAP = [];

    /**
     * @var array
     */
    protected const STRATEGY_GROUP_TO_FORM_TYPE_MAP = [
        self::GROUP_HARD => MerchantRelationshipHardThresholdFormMapper::class,
        self::GROUP_HARD_MAX => MerchantRelationshipHardMaximumThresholdFormMapper::class,
        self::GROUP_SOFT => MerchantRelationshipSoftThresholdFormMapper::class,
    ];

    /**
     * @deprecated Will be removed in the next major.
     * @var array
     */
    protected const STRATEGY_TYPE_TO_DATA_PROVIDER_MAP = [];

    /**
     * @var array
     */
    protected const STRATEGY_GROUP_TO_DATA_PROVIDER_MAP = [
        self::GROUP_HARD => MerchantRelationshipHardThresholdDataProvider::class,
        self::GROUP_HARD_MAX => MerchantRelationshipHardMaximumThresholdDataProvider::class,
        self::GROUP_SOFT => MerchantRelationshipSoftThresholdDataProvider::class,
    ];

    /**
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return array
     */
    public function getStrategyTypeToFormTypeMap(): array
    {
        return static::STRATEGY_TYPE_TO_FORM_TYPE_MAP;
    }

    /**
     * @api
     *
     * @phpstan-return array<class-string<\Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\MerchantRelationshipThresholdFormMapperInterface>>
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
     * @return array
     */
    public function getStrategyTypeToDataProviderMap(): array
    {
        return static::STRATEGY_TYPE_TO_DATA_PROVIDER_MAP;
    }

    /**
     * @api
     *
     * @phpstan-return array<class-string<\Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\ThresholdStrategyGroupDataProviderInterface>>
     *
     * @return array<string>
     */
    public function getStrategyGroupToDataProviderMap(): array
    {
        return static::STRATEGY_GROUP_TO_DATA_PROVIDER_MAP;
    }
}
