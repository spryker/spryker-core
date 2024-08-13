<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Dependency\Facade;

use Generated\Shared\Transfer\SalesPaymentCollectionTransfer;
use Generated\Shared\Transfer\SalesPaymentCriteriaTransfer;

class SalesPaymentMerchantToSalesPaymentFacadeBridge implements SalesPaymentMerchantToSalesPaymentFacadeInterface
{
    /**
     * @var \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface
     */
    protected $salesPaymentFacade;

    /**
     * @param \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface $salesPaymentFacade
     */
    public function __construct($salesPaymentFacade)
    {
        $this->salesPaymentFacade = $salesPaymentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentCriteriaTransfer $salesPaymentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentCollectionTransfer
     */
    public function getSalesPaymentCollection(SalesPaymentCriteriaTransfer $salesPaymentCriteriaTransfer): SalesPaymentCollectionTransfer
    {
        return $this->salesPaymentFacade->getSalesPaymentCollection($salesPaymentCriteriaTransfer);
    }
}
