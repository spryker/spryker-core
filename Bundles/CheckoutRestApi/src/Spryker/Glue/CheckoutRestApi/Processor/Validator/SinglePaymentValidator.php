<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class SinglePaymentValidator implements SinglePaymentValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validate(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestErrorCollectionTransfer
    {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        if ($restCheckoutRequestAttributesTransfer->getPayments()->count() > 1) {
             $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_MULTIPLE_PAYMENTS_NOT_ALLOWED)
                ->setDetail(CheckoutRestApiConfig::RESPONSE_DETAILS_MULTIPLE_PAYMENTS_NOT_ALLOWED);
            $restErrorCollectionTransfer->addRestError($restErrorMessageTransfer);
        }

        return $restErrorCollectionTransfer;
    }
}
