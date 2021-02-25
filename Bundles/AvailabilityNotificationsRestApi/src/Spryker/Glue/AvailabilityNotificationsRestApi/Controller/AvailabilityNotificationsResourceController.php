<?php

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
     *     "getCollection": {
     *          "path": "/availability-notifications",
     *          "summary": [
     *              "Retrieves a collection of notification subscriptions about products availability."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this
            ->getFactory()
            ->createAvailabilityNotificationReader()
            ->getAvailabilityNotifications($restRequest)
        ;
    }

    /**
     * @Glue({
     *     "post": {
     *          "path": "/availability-notifications",
     *          "summary": [
     *              "Subscribe to receive a notification by email when product is back in stock."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestAvailabilityNotificationsAttributesTransfer",
     *          "responses": {
     *              "400": "Email is not valid.",
     *              "404": "Product not found."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this
            ->getFactory()
            ->createAvailabilityNotificationSubscriber()
            ->subscribe($restRequest)
        ;
    }

    /**
     * @Glue({
     *     "delete": {
     *          "path": "/availability-notifications/{subscriptionKey}",
     *          "summary": [
     *              "Unsubscribe from receiving notifications."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "404": "Availability notification not found."
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
            ->unsubscribeBySubscriptionKey($restRequest)
        ;
    }
}
