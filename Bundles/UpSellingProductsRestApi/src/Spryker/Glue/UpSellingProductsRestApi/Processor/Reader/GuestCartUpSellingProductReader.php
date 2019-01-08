<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UpSellingProductsRestApi\Processor\Reader;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class GuestCartUpSellingProductReader extends UpSellingProductReader
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readUpSellingProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getUser()) {
            return $this->createAnonymousCustomerUniqueIdEmptyError();
        }

        return parent::readUpSellingProducts($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    protected function findParentResourceByType(RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createAnonymousCustomerUniqueIdEmptyError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CartsRestApiConfig::EXCEPTION_MESSAGE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }
}
