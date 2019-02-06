<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCart;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as SharedCartsRestApiConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AnonymousCustomerUniqueIdValidator implements AnonymousCustomerUniqueIdValidatorInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\CartsRestApiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\CartsRestApi\CartsRestApiConfig $config
     */
    public function __construct(CartsRestApiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $httpRequest, RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        if (!$httpRequest->headers->has(SharedCartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID)) {
            return null;
        }

        if (empty($httpRequest->headers->get(SharedCartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID))
            && in_array($restRequest->getResource()->getType(), $this->config->getGuestCartResources(), true)
        ) {
            return (new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setCode(SharedCartsRestApiConfig::RESPONSE_CODE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY)
                ->setDetail(SharedCartsRestApiConfig::EXCEPTION_MESSAGE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY);
        }

        return null;
    }
}
