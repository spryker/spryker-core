<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListsRestApi\Business\Expander;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CustomerIdentifierExpander implements CustomerIdentifierExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function expandCustomerIdentifierWithCustomerProductListCollection(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer {
        $customerProductListCollectionTransfer = $customerTransfer->getCustomerProductListCollection();

        if (!$customerProductListCollectionTransfer) {
            return $customerIdentifierTransfer;
        }

        return $customerIdentifierTransfer->setCustomerProductListCollection($customerProductListCollectionTransfer);
    }
}
