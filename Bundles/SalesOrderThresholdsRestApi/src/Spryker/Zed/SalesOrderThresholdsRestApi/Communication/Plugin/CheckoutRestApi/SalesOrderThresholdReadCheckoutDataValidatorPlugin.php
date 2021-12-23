<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdsRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\ReadCheckoutDataValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderThresholdsRestApi\Business\SalesOrderThresholdsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderThresholdsRestApi\SalesOrderThresholdsRestApiConfig getConfig()
 */
class SalesOrderThresholdReadCheckoutDataValidatorPlugin extends AbstractPlugin implements ReadCheckoutDataValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CheckoutDataTransfer.quote` to be set.
     * - Requires `CheckoutDataTransfer.quote.currency` to be set.
     * - Finds applicable thresholds.
     * - Adds error messages if threshold conditions are not matched.
     * - Returns `CheckoutResponseTransfer.isSuccessful` equal to `true` if validation passed, `false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        return $this->getFacade()->validateSalesOrderThresholdsCheckoutData($checkoutDataTransfer);
    }
}
