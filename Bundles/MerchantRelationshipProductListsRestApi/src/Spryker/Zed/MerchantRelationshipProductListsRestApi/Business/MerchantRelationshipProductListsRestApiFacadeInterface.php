<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListsRestApi\Business;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface MerchantRelationshipProductListsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Expects `CustomerTransfer.customerProductListCollection` to be set.
     * - Does nothing if `CustomerTransfer.customerProductListCollection` is not set.
     * - Expands `CustomerIdentifierTransfer` with customer's product list collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function expandCustomerIdentifierWithCustomerProductListCollection(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer;
}
