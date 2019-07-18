<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\MerchantRelationshipHardThresholdDataProvider;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\MerchantRelationshipSoftThresholdDataProvider;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\MerchantRelationshipHardThresholdFormMapper;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\MerchantRelationshipSoftThresholdFormMapper;

class MerchantRelationshipSalesOrderThresholdGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\MerchantRelationshipThresholdType::OPTION_CURRENCY_CODE
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
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::STRATEGY_KEY
     */
    public const HARD_TYPE_STRATEGY = 'hard-minimum-threshold';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::STRATEGY_KEY
     */
    public const SOFT_TYPE_STRATEGY_MESSAGE = 'soft-minimum-threshold';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::STRATEGY_KEY
     */
    public const SOFT_TYPE_STRATEGY_FIXED = 'soft-minimum-threshold-fixed-fee';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::STRATEGY_KEY
     */
    public const SOFT_TYPE_STRATEGY_FLEXIBLE = 'soft-minimum-threshold-flexible-fee';

    public const STORE_CURRENCY_DELIMITER = ';';

    /**
     * @deprecated Will be removed in the next major.
     */
    protected const STRATEGY_TYPE_TO_FORM_TYPE_MAP = [];

    protected const STRATEGY_GROUP_TO_FORM_TYPE_MAP = [
        self::GROUP_HARD => MerchantRelationshipHardThresholdFormMapper::class,
        self::GROUP_SOFT => MerchantRelationshipSoftThresholdFormMapper::class,
    ];

    /**
     * @deprecated Will be removed in the next major.
     */
    protected const STRATEGY_TYPE_TO_DATA_PROVIDER_MAP = [];

    protected const STRATEGY_GROUP_TO_DATA_PROVIDER_MAP = [
        self::GROUP_HARD => MerchantRelationshipHardThresholdDataProvider::class,
        self::GROUP_SOFT => MerchantRelationshipSoftThresholdDataProvider::class,
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
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\MerchantRelationshipThresholdFormMapperInterface[]
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
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\ThresholdStrategyGroupDataProviderInterface[]
     */
    public function getStrategyGroupToDataProviderMap(): array
    {
        return static::STRATEGY_GROUP_TO_DATA_PROVIDER_MAP;
    }
}
