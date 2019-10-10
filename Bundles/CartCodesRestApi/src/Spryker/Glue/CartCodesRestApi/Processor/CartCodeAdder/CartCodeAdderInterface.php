<?php

namespace Spryker\CartCodesRestApi\src\Spryker\Glue\CartCodesRestApi\Processor\CartCodeAdder;

use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Generated\Shared\Transfer\RestDiscountRequestAttributesTransfer;
use Generated\Shared\Transfer\RestDiscountsRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface CartCodeAdderInterface
{
    /**
     * @param RestRequestInterface $restRequest
     * @param RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer
     *
     * @return RestResponseInterface
     */
    public function addCandidate(
        RestRequestInterface $restRequest,
        RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer
    ): RestResponseInterface;
}
