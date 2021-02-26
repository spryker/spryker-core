<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Subscriber;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToStoreClientInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;

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
         * @var \Generated\Shared\Transfer\RestAvailabilityNotificationsAttributesTransfer $restAvailabilityNotificationsAttributesTransfer
         */
        $restAvailabilityNotificationsAttributesTransfer = $restRequest->getResource()->getAttributes();

        $availabilityNotificationSubscriptionTransfer = new AvailabilityNotificationSubscriptionTransfer();
        $availabilityNotificationSubscriptionTransfer->fromArray(
            array_merge(
                $restAvailabilityNotificationsAttributesTransfer->toArray(),
                [
                    "locale" => $localeTransfer,
                    "store" => $storeTransfer,
                    "customerReference" => $customerReference,
                ]
            )
        );

        $availabilityNotificationSubscriptionResponseTransfer = $this->availabilityNotificationClient->subscribe($availabilityNotificationSubscriptionTransfer);

        dd($availabilityNotificationSubscriptionResponseTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function unsubscribeBySubscriptionKey(RestRequestInterface $restRequest): RestResponseInterface
    {
        $availabilityNotificationSubscriptionResponseTransfer = $this->availabilityNotificationClient->unsubscribeBySubscriptionKey((new AvailabilityNotificationSubscriptionTransfer)->setSubscriptionKey($restRequest->getResource()->getId()));

        dd($availabilityNotificationSubscriptionResponseTransfer);
    }
}
