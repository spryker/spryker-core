<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantRelationshipProductListsRestApi\Expander;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomerExpander implements CustomerExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerWithCustomerProductListCollection(
        CustomerTransfer $customerTransfer,
        RestRequestInterface $restRequest
    ): CustomerTransfer {
        $restUserTransfer = $restRequest->getRestUser();

        if (!$restUserTransfer) {
            return $customerTransfer;
        }

        $customerProductListCollectionTransfer = $restUserTransfer->getCustomerProductListCollection();

        return $customerTransfer->setCustomerProductListCollection($customerProductListCollectionTransfer);
    }
}
