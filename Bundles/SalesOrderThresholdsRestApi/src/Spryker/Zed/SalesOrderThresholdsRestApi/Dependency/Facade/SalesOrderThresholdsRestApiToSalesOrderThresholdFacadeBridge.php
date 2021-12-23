<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class SalesOrderThresholdsRestApiToSalesOrderThresholdFacadeBridge implements SalesOrderThresholdsRestApiToSalesOrderThresholdFacadeInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface
     */
    protected $salesOrderThresholdFacade;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface $salesOrderThresholdFacade
     */
    public function __construct($salesOrderThresholdFacade)
    {
        $this->salesOrderThresholdFacade = $salesOrderThresholdFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateSalesOrderThresholdsCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        return $this->salesOrderThresholdFacade->validateSalesOrderThresholdsCheckoutData($checkoutDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithSalesOrderThresholdValues(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->salesOrderThresholdFacade->expandQuoteWithSalesOrderThresholdValues($quoteTransfer);
    }
}
