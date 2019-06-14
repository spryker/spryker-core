<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Customer;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCustomerTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomerMapper implements CustomerMapperInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCustomerTransfer
     */
    public function mapRestCustomerTransferFromRestCheckoutRequest(
        RestRequestInterface $restRequest,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCustomerTransfer {
        $restCustomerTransfer = new RestCustomerTransfer();

        if (!$restRequest->getUser()) {
            return $restCustomerTransfer;
        }

        if ($restCheckoutRequestAttributesTransfer->getCustomer()) {
            $restCustomerTransfer->fromArray(
                $restCheckoutRequestAttributesTransfer->getCustomer()->toArray(),
                true
            );
        }

        $restCustomerTransfer->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());

        if ($restRequest->getUser()->getSurrogateIdentifier()) {
            return $restCustomerTransfer->setIdCustomer((int)$restRequest->getUser()->getSurrogateIdentifier());
        }

        return $restCustomerTransfer->setIdCustomer(null);
    }
}
