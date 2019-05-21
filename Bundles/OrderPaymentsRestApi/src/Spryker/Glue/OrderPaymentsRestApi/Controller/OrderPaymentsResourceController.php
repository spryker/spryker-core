<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderPaymentsRestApi\Controller;

use Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\OrderPaymentsRestApi\OrderPaymentsRestApiFactory getFactory()
 */
class OrderPaymentsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Updates order payment."
     *          ]
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRequestInterface $restRequest, RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createOrderPaymentUpdater()
            ->updateOrderPayment($restRequest, $restOrderPaymentsAttributesTransfer);
    }
}
