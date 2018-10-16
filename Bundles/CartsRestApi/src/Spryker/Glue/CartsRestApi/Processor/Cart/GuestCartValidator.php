<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Cart;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestCartValidator implements GuestCartValidatorInterface
{
    protected const GUEST_CART_RESOURCES = [
        CartsRestApiConfig::RESOURCE_GUEST_CARTS,
        CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
    ];

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $httpRequest, RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        if (!$httpRequest->headers->has(CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID)) {
            return null;
        }

        if (empty($httpRequest->headers->get(CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID))
            && in_array($restRequest->getResource()->getType(), static::GUEST_CART_RESOURCES, true)
        ) {
            return (new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setCode(CartsRestApiConfig::RESPONSE_CODE_CUSTOMER_UNAUTHORIZED)
                ->setDetail(CartsRestApiConfig::RESPONSE_DETAILS_CUSTOMER_UNAUTHORIZED);
        }

        return null;
    }
}
