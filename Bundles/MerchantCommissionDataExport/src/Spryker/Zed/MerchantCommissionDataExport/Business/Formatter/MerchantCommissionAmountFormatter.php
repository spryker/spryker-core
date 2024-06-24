<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Business\Formatter;

use Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer;
use Spryker\Zed\MerchantCommissionDataExport\Dependency\Facade\MerchantCommissionDataExportToMerchantCommissionFacadeInterface;

class MerchantCommissionAmountFormatter implements MerchantCommissionAmountFormatterInterface
{
    /**
     * @uses \Spryker\Zed\MerchantCommissionDataExport\Persistence\MerchantCommissionDataExportRepository::KEY_FIXED_AMOUNT_CONFIGURATION
     *
     * @var string
     */
    protected const KEY_FIXED_AMOUNT_CONFIGURATION = 'fixed_amount_configuration';

    /**
     * @var string
     */
    protected const KEY_AMOUNT = 'amount';

    /**
     * @var string
     */
    protected const KEY_CALCULATION_TYPE_PLUGIN = 'calculator_type_plugin';

    /**
     * @uses \Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap::COL_CODE
     *
     * @var string
     */
    protected const KEY_CURRENCY_CODE = 'spy_currency.code';

    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionAmountTableMap::COL_NET_AMOUNT
     *
     * @var string
     */
    protected const KEY_NET_AMOUNT = 'spy_merchant_commission_amount.net_amount';

    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionAmountTableMap::COL_GROSS_AMOUNT
     *
     * @var string
     */
    protected const KEY_GROSS_AMOUNT = 'spy_merchant_commission_amount.gross_amount';

    /**
     * @var \Spryker\Zed\MerchantCommissionDataExport\Dependency\Facade\MerchantCommissionDataExportToMerchantCommissionFacadeInterface
     */
    protected MerchantCommissionDataExportToMerchantCommissionFacadeInterface $merchantCommissionFacade;

    /**
     * @param \Spryker\Zed\MerchantCommissionDataExport\Dependency\Facade\MerchantCommissionDataExportToMerchantCommissionFacadeInterface $merchantCommissionFacade
     */
    public function __construct(MerchantCommissionDataExportToMerchantCommissionFacadeInterface $merchantCommissionFacade)
    {
        $this->merchantCommissionFacade = $merchantCommissionFacade;
    }

    /**
     * @param array<string, mixed> $merchantCommissionData
     *
     * @return array<string, mixed>
     */
    public function formatMerchantCommissionAmount(array $merchantCommissionData): array
    {
        foreach ($merchantCommissionData as $key => $merchantCommissionRowData) {
            if (isset($merchantCommissionRowData[static::KEY_AMOUNT])) {
                $merchantCommissionAmount = $this->transformMerchantCommissionAmount(
                    $merchantCommissionRowData[static::KEY_CALCULATION_TYPE_PLUGIN],
                    (int)$merchantCommissionRowData[static::KEY_AMOUNT],
                );

                $merchantCommissionData[$key][static::KEY_AMOUNT] = $merchantCommissionAmount;
            }

            if (
                !isset($merchantCommissionRowData[static::KEY_FIXED_AMOUNT_CONFIGURATION])
                || $merchantCommissionRowData[static::KEY_FIXED_AMOUNT_CONFIGURATION] === ''
            ) {
                continue;
            }

            $merchantCommissionData[$key][static::KEY_FIXED_AMOUNT_CONFIGURATION] = $this->formatFixedAmountConfiguration(
                $merchantCommissionRowData[static::KEY_FIXED_AMOUNT_CONFIGURATION],
                $merchantCommissionRowData[static::KEY_CALCULATION_TYPE_PLUGIN],
            );
        }

        return $merchantCommissionData;
    }

    /**
     * @param list<array<string, mixed>> $merchantCommissionAmountData
     * @param string $merchantCommissionCalculatorPluginType
     *
     * @return string
     */
    protected function formatFixedAmountConfiguration(array $merchantCommissionAmountData, string $merchantCommissionCalculatorPluginType): string
    {
        $fixedAmountConfiguration = [];
        foreach ($merchantCommissionAmountData as $merchantCommissionAmountRowData) {
            $fixedAmountConfiguration[] = sprintf(
                '%s|%s|%s',
                $merchantCommissionAmountRowData[static::KEY_CURRENCY_CODE],
                $this->transformMerchantCommissionAmount(
                    $merchantCommissionCalculatorPluginType,
                    (int)$merchantCommissionAmountRowData[static::KEY_NET_AMOUNT],
                ),
                $this->transformMerchantCommissionAmount(
                    $merchantCommissionCalculatorPluginType,
                    (int)$merchantCommissionAmountRowData[static::KEY_GROSS_AMOUNT],
                ),
            );
        }

        return implode(',', $fixedAmountConfiguration);
    }

    /**
     * @param string $merchantCommissionCalculatorPluginType
     * @param int $amount
     *
     * @return float
     */
    protected function transformMerchantCommissionAmount(string $merchantCommissionCalculatorPluginType, int $amount): float
    {
        $merchantCommissionAmountTransformerRequestTransfer = (new MerchantCommissionAmountTransformerRequestTransfer())
            ->setCalculatorTypePlugin($merchantCommissionCalculatorPluginType)
            ->setAmountFromPersistence($amount);

        return $this->merchantCommissionFacade->transformMerchantCommissionAmountFromPersistence(
            $merchantCommissionAmountTransformerRequestTransfer,
        );
    }
}
