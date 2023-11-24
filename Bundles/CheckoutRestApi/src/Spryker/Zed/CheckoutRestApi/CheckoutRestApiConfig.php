<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CheckoutRestApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function deleteCartAfterOrderCreation()
    {
        return true;
    }

    /**
     * Specification:
     * - If set to `true` a stack of {@link \Spryker\Zed\CalculationExtension\Dependency\Plugin\QuotePostRecalculatePluginStrategyInterface} will be executed after quote recalculation.
     * - Impacts {@link \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade::getCheckoutData()} method.
     * - Impacts {@link \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade::placeOrder()} method.
     *
     * @api
     *
     * @return bool
     */
    public function shouldExecuteQuotePostRecalculationPlugins(): bool
    {
        return true;
    }

    /**
     * Specification:
     * - If set to `true`, quote recalculation in a stack of {@link \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface} will be enabled.
     * - Impacts {@link \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade::getCheckoutData()} method.
     * - Impacts {@link \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade::placeOrder()} method.
     *
     * @api
     *
     * @return bool
     */
    public function isRecalculationEnabledForQuoteMapperPlugins(): bool
    {
        return true;
    }
}
