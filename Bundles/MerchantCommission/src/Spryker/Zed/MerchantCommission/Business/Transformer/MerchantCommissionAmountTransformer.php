<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Transformer;

use Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer;
use Spryker\Zed\MerchantCommission\Business\Resolver\MerchantCommissionCalculatorPluginResolverInterface;

class MerchantCommissionAmountTransformer implements MerchantCommissionAmountTransformerInterface
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
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
     *
     * @return int
     */
    public function transformForPersistence(MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer): int
    {
        $merchantCommissionCalculatorPlugin = $this->merchantCommissionCalculatorPluginResolver->getMerchantCommissionCalculatorPlugin(
            $merchantCommissionAmountTransformerRequestTransfer->getCalculatorTypePluginOrFail(),
        );

        return $merchantCommissionCalculatorPlugin->transformAmountForPersistence(
            $merchantCommissionAmountTransformerRequestTransfer->getAmountForPersistenceOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
     *
     * @return float
     */
    public function transformFromPersistence(MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer): float
    {
        $merchantCommissionCalculatorPlugin = $this->merchantCommissionCalculatorPluginResolver->getMerchantCommissionCalculatorPlugin(
            $merchantCommissionAmountTransformerRequestTransfer->getCalculatorTypePluginOrFail(),
        );

        return $merchantCommissionCalculatorPlugin->transformAmountFromPersistence(
            $merchantCommissionAmountTransformerRequestTransfer->getAmountFromPersistenceOrFail(),
        );
    }
}
