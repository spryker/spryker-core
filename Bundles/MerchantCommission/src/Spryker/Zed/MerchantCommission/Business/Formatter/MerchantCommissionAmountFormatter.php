<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Formatter;

use Generated\Shared\Transfer\MerchantCommissionAmountFormatRequestTransfer;
use Spryker\Zed\MerchantCommission\Business\Resolver\MerchantCommissionCalculatorPluginResolverInterface;

class MerchantCommissionAmountFormatter implements MerchantCommissionAmountFormatterInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Resolver\MerchantCommissionCalculatorPluginResolverInterface
     */
    protected MerchantCommissionCalculatorPluginResolverInterface $merchantCommissionCalculatorPluginResolver;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Resolver\MerchantCommissionCalculatorPluginResolverInterface $merchantCommissionCalculatorPluginResolver
     */
    public function __construct(MerchantCommissionCalculatorPluginResolverInterface $merchantCommissionCalculatorPluginResolver)
    {
        $this->merchantCommissionCalculatorPluginResolver = $merchantCommissionCalculatorPluginResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountFormatRequestTransfer $merchantCommissionAmountFormatRequestTransfer
     *
     * @return string
     */
    public function format(MerchantCommissionAmountFormatRequestTransfer $merchantCommissionAmountFormatRequestTransfer): string
    {
        $merchantCommissionCalculatorPlugin = $this->merchantCommissionCalculatorPluginResolver->getMerchantCommissionCalculatorPlugin(
            $merchantCommissionAmountFormatRequestTransfer->getCalculatorTypePluginOrFail(),
        );
        $currencyIsoCode = $merchantCommissionAmountFormatRequestTransfer->getCurrency()
            ? $merchantCommissionAmountFormatRequestTransfer->getCurrencyOrFail()->getCodeOrFail()
            : null;

        return $merchantCommissionCalculatorPlugin->formatMerchantCommissionAmount(
            $merchantCommissionAmountFormatRequestTransfer->getAmountOrFail(),
            $currencyIsoCode,
        );
    }
}
