<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiFactory getFactory()
 */
class AvailabilityNotificationsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Subscribe to receive a notification by email when product is back in stock."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestAvailabilityNotificationsAttributesTransfer",
     *          "responses": {
     *              "404": "Product not found.",
     *              "422": "Unprocessable entity."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this
            ->getFactory()
            ->createAvailabilityNotificationSubscriber()
            ->subscribe($restRequest);
    }

    /**
     * @Glue({
     *     "delete": {
     *          "summary": [
     *              "Unsubscribe from receiving notifications."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "400": "Resource id has not been specified.",
     *              "404": "Subscription doesn't exist."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this
            ->getFactory()
            ->createAvailabilityNotificationSubscriber()
            ->unsubscribeBySubscriptionKey($restRequest);
    }
}
