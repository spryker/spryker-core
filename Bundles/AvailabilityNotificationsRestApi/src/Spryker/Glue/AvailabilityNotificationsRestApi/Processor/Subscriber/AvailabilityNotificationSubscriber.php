<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Subscriber;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiConfig;
use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToStoreClientInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Symfony\Component\HttpFoundation\Response;

class AvailabilityNotificationSubscriber implements AvailabilityNotificationSubscriberInterface
{
    /**
     * @var \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface
     */
    protected $availabilityNotificationClient;

    /**
     * @var \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface
     */
    protected $restResponseBuilder;

    /**
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface $availabilityNotificationClient
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToStoreClientInterface $storeClient
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface $restResponseBuilder
     */
    public function __construct(
        AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface $availabilityNotificationClient,
        AvailabilityNotificationsRestApiToStoreClientInterface $storeClient,
        AvailabilityNotificationsRestResponseBuilderInterface $restResponseBuilder
    )
    {
        $this->availabilityNotificationClient = $availabilityNotificationClient;
        $this->storeClient = $storeClient;
        $this->restResponseBuilder = $restResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function subscribe(RestRequestInterface $restRequest): RestResponseInterface
    {
        $locale = $restRequest->getMetadata()->getLocale();
        $localeTransfer = (new LocaleTransfer())->setLocaleName($locale);
        $storeTransfer = $this->storeClient->getCurrentStore();
        $customerReference = $restRequest->getRestUser()
                    ? $restRequest->getRestUser()->getNaturalIdentifier()
                    : null
        ;

        /**
         * @var \Generated\Shared\Transfer\RestAvailabilityNotificationRequestAttributesTransfer $restAvailabilityNotificationRequestAttributesTransfer
         */
        $restAvailabilityNotificationRequestAttributesTransfer = $restRequest->getResource()->getAttributes();

        $availabilityNotificationSubscriptionTransfer = new AvailabilityNotificationSubscriptionTransfer();
        $availabilityNotificationSubscriptionTransfer->fromArray(
            array_merge(
                $restAvailabilityNotificationRequestAttributesTransfer->toArray(),
                [
                    "locale" => $localeTransfer,
                    "store" => $storeTransfer,
                    "customerReference" => $customerReference,
                ]
            )
        );

        $availabilityNotificationSubscriptionResponseTransfer = $this->availabilityNotificationClient->subscribe($availabilityNotificationSubscriptionTransfer);

        if (
            !$availabilityNotificationSubscriptionResponseTransfer->getIsSuccess() &&
            $availabilityNotificationSubscriptionResponseTransfer->getErrorMessage() === AvailabilityNotificationsRestApiConfig::RESPONSE_DETAIL_SUBSCRIPTION_ALREADY_EXISTS
        ) {
            return $this->restResponseBuilder->createSubscriptionAlreadyExistsErrorResponse();
        }

        if (
            !$availabilityNotificationSubscriptionResponseTransfer->getIsSuccess() &&
            $availabilityNotificationSubscriptionResponseTransfer->getErrorMessage() === AvailabilityNotificationsRestApiConfig::RESPONSE_DETAIL_PRODUCT_NOT_FOUND
        ) {
            return $this->restResponseBuilder->createProductNotFoundErrorResponse();
        }

        if (!$availabilityNotificationSubscriptionResponseTransfer->getIsSuccess()) {
            return $this->restResponseBuilder->createSomethingWentWrongErrorResponse();
        }

        return $this->restResponseBuilder->createSubscribeResponse($availabilityNotificationSubscriptionResponseTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function unsubscribeBySubscriptionKey(RestRequestInterface $restRequest): RestResponseInterface
    {
        $availabilityNotificationSubscriptionResponseTransfer = $this->availabilityNotificationClient->unsubscribeBySubscriptionKey((new AvailabilityNotificationSubscriptionTransfer)->setSubscriptionKey($restRequest->getResource()->getId()));

        if (
            !$availabilityNotificationSubscriptionResponseTransfer->getIsSuccess() &&
            $availabilityNotificationSubscriptionResponseTransfer->getErrorMessage() === AvailabilityNotificationsRestApiConfig::RESPONSE_DETAIL_SUBSCRIPTION_NOT_EXISTS
        ) {
            return $this->restResponseBuilder->createSubscriptionNotExistsErrorResponse();
        }

        if (!$availabilityNotificationSubscriptionResponseTransfer->getIsSuccess()) {
            return $this->restResponseBuilder->createSomethingWentWrongErrorResponse();
        }

        return $this->restResponseBuilder->createUnsubscribeResponse();
    }
}
