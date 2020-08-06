<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\CartCodeAdder;

use Generated\Shared\Transfer\RestCartCodeRequestAttributesTransfer;
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
    public function addDiscountCodeToCart(
        RestRequestInterface $restRequest,
        RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer
    ): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addDiscountCodeToGuestCart(
        RestRequestInterface $restRequest,
        RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer
    ): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartCodeRequestAttributesTransfer $restCartCodeRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addCartCodeToCart(
        RestRequestInterface $restRequest,
        RestCartCodeRequestAttributesTransfer $restCartCodeRequestAttributesTransfer
    ): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartCodeRequestAttributesTransfer $restCartCodeRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addCartCodeToGuestCart(
        RestRequestInterface $restRequest,
        RestCartCodeRequestAttributesTransfer $restCartCodeRequestAttributesTransfer
    ): RestResponseInterface;
}
