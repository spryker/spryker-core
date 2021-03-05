<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\RestAvailabilityNotificationsAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiConfig;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Mapper\AvailabilityNotificationMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class AvailabilityNotificationsRestResponseBuilder implements AvailabilityNotificationsRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Mapper\AvailabilityNotificationMapperInterface
     */
    protected $availabilityNotificationMapper;

    /**
     * @var \Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiConfig
     */
    protected $availabilityNotificationsRestApiConfig;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Mapper\AvailabilityNotificationMapperInterface $availabilityNotificationMapper
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiConfig $availabilityNotificationsRestApiConfig
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        AvailabilityNotificationMapperInterface $availabilityNotificationMapper,
        AvailabilityNotificationsRestApiConfig $availabilityNotificationsRestApiConfig
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->availabilityNotificationMapper = $availabilityNotificationMapper;
        $this->availabilityNotificationsRestApiConfig = $availabilityNotificationsRestApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAvailabilityNotificationResponse(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): RestResponseInterface {
        $restResource = $this->createAvailabilityNotificationResource($availabilityNotificationSubscriptionTransfer);

        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addResource($restResource);

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createEmptyResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer $availabilityNotificationSubscriptionCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAvailabilityNotificationCollectionResponse(
        AvailabilityNotificationSubscriptionCollectionTransfer $availabilityNotificationSubscriptionCollectionTransfer
    ): RestResponseInterface {

        if ($pagination = $availabilityNotificationSubscriptionCollectionTransfer->getPagination()) {
            $totalItems = $pagination->getNbResults() ?? 0;
            $limit = $pagination->getMaxPerPage() ?? 0;
        }

        $restResponse = $this->restResourceBuilder->createRestResponse($totalItems ?? 0, $limit ?? 0);

        foreach ($availabilityNotificationSubscriptionCollectionTransfer->getAvailabilityNotificationSubscriptions() as $availabilityNotificationSubscriptionTransfer) {
            $restResource = $this->createAvailabilityNotificationResource($availabilityNotificationSubscriptionTransfer);
            $restResponse->addResource($restResource);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer $availabilityNotificationSubscriptionResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSubscribeErrorResponse(
        AvailabilityNotificationSubscriptionResponseTransfer $availabilityNotificationSubscriptionResponseTransfer
    ): RestResponseInterface {
        $restErrorPayload = $this->availabilityNotificationsRestApiConfig->getErrorIdentifierToRestErrorMapping()[$availabilityNotificationSubscriptionResponseTransfer->getErrorMessage()] ?? $this->availabilityNotificationsRestApiConfig->getDefaultSubscribeRestError();

        return $this->createErrorResponse(
            (new RestErrorMessageTransfer())
                ->setCode($restErrorPayload[RestErrorMessageTransfer::CODE])
                ->setStatus($restErrorPayload[RestErrorMessageTransfer::STATUS])
                ->setDetail($restErrorPayload[RestErrorMessageTransfer::DETAIL])
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer $availabilityNotificationSubscriptionResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createUnsubscribeErrorResponse(
        AvailabilityNotificationSubscriptionResponseTransfer $availabilityNotificationSubscriptionResponseTransfer
    ): RestResponseInterface {
        $restErrorPayload = $this->availabilityNotificationsRestApiConfig->getErrorIdentifierToRestErrorMapping()[$availabilityNotificationSubscriptionResponseTransfer->getErrorMessage()] ?? $this->availabilityNotificationsRestApiConfig->getDefaultUnsubscribeRestError();

        return $this->createErrorResponse(
            (new RestErrorMessageTransfer())
                ->setCode($restErrorPayload[RestErrorMessageTransfer::CODE])
                ->setStatus($restErrorPayload[RestErrorMessageTransfer::STATUS])
                ->setDetail($restErrorPayload[RestErrorMessageTransfer::DETAIL])
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorResponse(RestErrorMessageTransfer $restErrorMessageTransfer): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createAvailabilityNotificationResource(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): RestResourceInterface {
        $restAvailabilityNotificationsAttributesTransfer = $this
            ->availabilityNotificationMapper
            ->mapAvailabilityNotificationSubscriptionTransferToRestAvailabilityNotificationsAttributesTransfer(
                $availabilityNotificationSubscriptionTransfer,
                new RestAvailabilityNotificationsAttributesTransfer()
            );

        return $this->restResourceBuilder->createRestResource(
            AvailabilityNotificationsRestApiConfig::RESOURCE_AVAILABILITY_NOTIFICATIONS,
            $availabilityNotificationSubscriptionTransfer->getSubscriptionKey(),
            $restAvailabilityNotificationsAttributesTransfer
        );
    }
}
