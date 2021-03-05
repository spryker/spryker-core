<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\RestAvailabilityNotificationsAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiConfig;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Mapper\AvailabilityNotificationMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Mapper\AvailabilityNotificationMapperInterface $availabilityNotificationMapper
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder, AvailabilityNotificationMapperInterface $availabilityNotificationMapper)
    {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->availabilityNotificationMapper = $availabilityNotificationMapper;
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
        if ($availabilityNotificationSubscriptionCollectionTransfer->getPagination()) {
            $totalItems = $availabilityNotificationSubscriptionCollectionTransfer->getPagination()->getNbResults() ?? 0;
            $limit = $availabilityNotificationSubscriptionCollectionTransfer->getPagination()->getMaxPerPage() ?? 0;
        }

        $restResponse = $this->restResourceBuilder->createRestResponse($totalItems ?? 0, $limit ?? 0);

        foreach ($availabilityNotificationSubscriptionCollectionTransfer->getAvailabilityNotificationSubscriptions() as $availabilityNotificationSubscriptionTransfer) {
            $restResource = $this->createAvailabilityNotificationResource($availabilityNotificationSubscriptionTransfer);
            $restResponse->addResource($restResource);
        }

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductNotFoundErrorResponse(): RestResponseInterface
    {
        return $this->createErrorResponse(AvailabilityNotificationsRestApiConfig::RESPONSE_CODE_PRODUCT_NOT_FOUND, AvailabilityNotificationsRestApiConfig::RESPONSE_DETAIL_PRODUCT_NOT_FOUND);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSubscriptionAlreadyExistsErrorResponse(): RestResponseInterface
    {
        return $this->createErrorResponse(AvailabilityNotificationsRestApiConfig::RESPONSE_CODE_SUBSCRIPTION_ALREADY_EXISTS, AvailabilityNotificationsRestApiConfig::RESPONSE_DETAIL_SUBSCRIPTION_ALREADY_EXISTS);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSubscriptionNotExistsErrorResponse(): RestResponseInterface
    {
        return $this->createErrorResponse(AvailabilityNotificationsRestApiConfig::RESPONSE_CODE_SUBSCRIPTION_DOES_NOT_EXIST, AvailabilityNotificationsRestApiConfig::RESPONSE_DETAIL_SUBSCRIPTION_DOES_NOT_EXIST);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSomethingWentWrongErrorResponse(): RestResponseInterface
    {
        return $this->createErrorResponse(AvailabilityNotificationsRestApiConfig::RESPONSE_CODE_SOMETHING_WENT_WRONG, AvailabilityNotificationsRestApiConfig::RESPONSE_DETAIL_SOMETHING_WENT_WRONG);
    }

    /**
     * @param string $code
     * @param string $detail
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createErrorResponse(string $code, string $detail): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode($code)
            ->setDetail($detail)
            ->setStatus(Response::HTTP_BAD_REQUEST);

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
