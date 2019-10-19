<?php

namespace Spryker\CartCodesRestApi\src\Spryker\Glue\CartCodesRestApi\Processor\CartCodeAdder;

use Generated\Shared\Transfer\RestDiscountsRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface CartCodeAdderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addCandidate(
        RestRequestInterface $restRequest,
        RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer
    ): RestResponseInterface;
}
