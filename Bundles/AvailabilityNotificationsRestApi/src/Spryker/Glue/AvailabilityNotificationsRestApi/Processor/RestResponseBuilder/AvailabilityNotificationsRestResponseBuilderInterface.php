<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface AvailabilityNotificationsRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer $availabilityNotificationSubscriptionResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSubscribeResponse(AvailabilityNotificationSubscriptionResponseTransfer $availabilityNotificationSubscriptionResponseTransfer): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createUnsubscribeResponse(): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer $availabilityNotificationSubscriptionCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAvailabilityNotificationCollectionResponse(AvailabilityNotificationSubscriptionCollectionTransfer $availabilityNotificationSubscriptionCollectionTransfer): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductNotFoundErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSubscriptionAlreadyExistsErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSubscriptionNotExistsErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSomethingWentWrongErrorResponse(): RestResponseInterface;
}
