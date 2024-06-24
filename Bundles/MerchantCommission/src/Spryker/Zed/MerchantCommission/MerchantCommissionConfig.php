<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\MerchantCommission\Business\Exception\MerchantCommissionPriceTypePerStoreUndefinedException;

class MerchantCommissionConfig extends AbstractBundleConfig
{
    /**
     * @var array<string, string>
     */
    protected const MERCHANT_COMMISSION_PRICE_MODE_PER_STORE = [];

    /**
     * @var list<string>
     */
    protected const EXCLUDED_MERCHANTS_FROM_COMMISSION = [];

    /**
     * @var string
     */
    protected const RULE_ENGINE_MERCHANT_COMMISSION_DOMAIN_NAME = 'merchant_commission';

    /**
     * Specification:
     * - Retrieves the list of merchants who are not subject to commissions.
     * - Used by the merchant commission calculation and recalculation operations.
     *
     * @api
     *
     * @return list<string>
     */
    public function getExcludedMerchantsFromCommission(): array
    {
        return static::EXCLUDED_MERCHANTS_FROM_COMMISSION;
    }

    /**
     * Specification:
     * - This method is used to get the commission price mode for a given store.
     *
     * @api
     *
     * @param string $storeName
     *
     * @throws \Spryker\Zed\MerchantCommission\Business\Exception\MerchantCommissionPriceTypePerStoreUndefinedException
     *
     * @return string
     */
    public function getMerchantCommissionPriceModeForStore(string $storeName): string
    {
        return static::MERCHANT_COMMISSION_PRICE_MODE_PER_STORE[$storeName]
            ?? throw new MerchantCommissionPriceTypePerStoreUndefinedException(sprintf(
                'The merchant commission price type config for the store "%s" does not exist.
                 Please add it by configuring the MerchantCommissionConfig::MERCHANT_COMMISSION_PRICE_MODE_PER_STORE.',
                $storeName,
            ));
    }

    /**
     * Specification:
     * - This method is used to configure round mode for percentage merchant commission calculation.
     * - See {@link https://www.php.net/manual/en/math.constants.php} for more details.
     *
     * @api
     *
     * @phpstan-return 1|2|3|4
     *
     * @return int
     */
    public function getPercentageMerchantCommissionCalculationRoundMode(): int
    {
        return PHP_ROUND_HALF_UP;
    }

    /**
     * Specification:
     * - Returns merchant commission's domain name used by rule engine.
     *
     * @api
     *
     * @return string
     */
    public function getRuleEngineMerchantCommissionDomainName(): string
    {
        return static::RULE_ENGINE_MERCHANT_COMMISSION_DOMAIN_NAME;
    }
}
