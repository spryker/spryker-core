<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiConfig;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Mapper\AvailabilityNotificationMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
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
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer $availabilityNotificationSubscriptionResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSubscribeResponse(AvailabilityNotificationSubscriptionResponseTransfer $availabilityNotificationSubscriptionResponseTransfer): RestResponseInterface
    {
        $restAvailabilityNotificationsAttributesTransfer = $this
            ->availabilityNotificationMapper
            ->mapAvailabilityNotificationSubscriptionTransferToRestAvailabilityNotificationsAttributesTransfer(
                $availabilityNotificationSubscriptionResponseTransfer->getAvailabilityNotificationSubscriptionOrFail()
            )
        ;
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResource = $this->restResourceBuilder->createRestResource(
            AvailabilityNotificationsRestApiConfig::RESOURCE_AVAILABILITY_NOTIFICATIONS,
            $availabilityNotificationSubscriptionResponseTransfer->getAvailabilityNotificationSubscription()->getSubscriptionKey(),
            $restAvailabilityNotificationsAttributesTransfer
        );
        $restResponse->addResource($restResource);

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createUnsubscribeResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()->setStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer $availabilityNotificationSubscriptionCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAvailabilityNotificationCollectionResponse(AvailabilityNotificationSubscriptionCollectionTransfer $availabilityNotificationSubscriptionCollectionTransfer): RestResponseInterface
    {
        $totalItems = 0;
        $limit = 0;
        if ($availabilityNotificationSubscriptionCollectionTransfer->getPagination()) {
            $totalItems = $availabilityNotificationSubscriptionCollectionTransfer->getPagination()->getNbResults() ?? 0;
            $limit = $availabilityNotificationSubscriptionCollectionTransfer->getPagination()->getMaxPerPage() ?? 0;
        }

        $restResponse = $this->restResourceBuilder->createRestResponse($totalItems, $limit);

        foreach ($availabilityNotificationSubscriptionCollectionTransfer->getAvailabilityNotificationSubscriptions() as $availabilityNotificationSubscriptionTransfer) {
            $restAvailabilityNotificationsAttributesTransfer = $this
                ->availabilityNotificationMapper
                ->mapAvailabilityNotificationSubscriptionTransferToRestAvailabilityNotificationsAttributesTransfer(
                    $availabilityNotificationSubscriptionTransfer
                )
            ;
            $restResource = $this->restResourceBuilder->createRestResource(
                AvailabilityNotificationsRestApiConfig::RESOURCE_AVAILABILITY_NOTIFICATIONS,
                $availabilityNotificationSubscriptionTransfer->getSubscriptionKey(),
                $restAvailabilityNotificationsAttributesTransfer
            );
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
        return $this->createErrorResponse(AvailabilityNotificationsRestApiConfig::RESPONSE_CODE_SUBSCRIPTION_NOT_EXISTS, AvailabilityNotificationsRestApiConfig::RESPONSE_DETAIL_SUBSCRIPTION_NOT_EXISTS);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSomethingWentWrongErrorResponse(): RestResponseInterface
    {
        return $this->createErrorResponse(AvailabilityNotificationsRestApiConfig::RESPONSE_CODE_SOMETHING_WENT_WRONG, AvailabilityNotificationsRestApiConfig::RESPONSE_DETAIL_SOMETHING_WENT_WRONG);
    }

    protected function createErrorResponse(string $code, string $detail): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode($code)
            ->setDetail($detail)
            ->setStatus(Response::HTTP_BAD_REQUEST)
        ;
        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }
}
