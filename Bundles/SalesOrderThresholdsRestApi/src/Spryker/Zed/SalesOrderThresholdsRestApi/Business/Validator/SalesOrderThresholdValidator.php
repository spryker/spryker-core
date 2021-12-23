<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdsRestApi\Business\Validator;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\SalesOrderThresholdsRestApi\Dependency\Facade\SalesOrderThresholdsRestApiToSalesOrderThresholdFacadeInterface;

class SalesOrderThresholdValidator implements SalesOrderThresholdValidatorInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThresholdsRestApi\Dependency\Facade\SalesOrderThresholdsRestApiToSalesOrderThresholdFacadeInterface
     */
    protected $salesOrderThresholdFacade;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdsRestApi\Dependency\Facade\SalesOrderThresholdsRestApiToSalesOrderThresholdFacadeInterface $salesOrderThresholdFacade
     */
    public function __construct(SalesOrderThresholdsRestApiToSalesOrderThresholdFacadeInterface $salesOrderThresholdFacade)
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
}
