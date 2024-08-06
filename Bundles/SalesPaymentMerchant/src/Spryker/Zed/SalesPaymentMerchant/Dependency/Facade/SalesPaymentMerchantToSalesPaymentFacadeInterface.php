<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Dependency\Facade;

use Generated\Shared\Transfer\SalesPaymentCollectionTransfer;
use Generated\Shared\Transfer\SalesPaymentCriteriaTransfer;

interface SalesPaymentMerchantToSalesPaymentFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesPaymentCriteriaTransfer $salesPaymentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentCollectionTransfer
     */
    public function getSalesPaymentCollection(SalesPaymentCriteriaTransfer $salesPaymentCriteriaTransfer): SalesPaymentCollectionTransfer;
}
