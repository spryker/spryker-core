<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Subscriber;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToStoreClientInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

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
    protected $availabilityNotificationsRestResponseBuilder;

    /**
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface $availabilityNotificationClient
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToStoreClientInterface $storeClient
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface $availabilityNotificationsRestResponseBuilder
     */
    public function __construct(
        AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface $availabilityNotificationClient,
        AvailabilityNotificationsRestApiToStoreClientInterface $storeClient,
        AvailabilityNotificationsRestResponseBuilderInterface $availabilityNotificationsRestResponseBuilder
    ) {
        $this->availabilityNotificationClient = $availabilityNotificationClient;
        $this->storeClient = $storeClient;
        $this->availabilityNotificationsRestResponseBuilder = $availabilityNotificationsRestResponseBuilder;
    }

    /**
     * @uses \Spryker\Glue\AvailabilityNotificationsRestApi\Plugin\GlueApplication\AvailabilityNotificationsResourceRoutePlugin::getResourceAttributesClassName()
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function subscribe(RestRequestInterface $restRequest): RestResponseInterface
    {
        $locale = $restRequest->getMetadata()->getLocale();
        $localeTransfer = (new LocaleTransfer())->setLocaleName($locale);
        $storeTransfer = $this->storeClient->getCurrentStore();
        $restUser = $restRequest->getRestUser();
        $customerReference = $restUser
                    ? $restUser->getNaturalIdentifier()
                    : null;

        /**
         * @var \Generated\Shared\Transfer\RestAvailabilityNotificationRequestAttributesTransfer $restAvailabilityNotificationRequestAttributesTransfer
         */
        $restAvailabilityNotificationRequestAttributesTransfer = $restRequest->getResource()->getAttributes();

        $availabilityNotificationSubscriptionTransfer = (new AvailabilityNotificationSubscriptionTransfer())
            ->fromArray($restAvailabilityNotificationRequestAttributesTransfer->toArray(), true)
            ->setLocale($localeTransfer)
            ->setStore($storeTransfer)
            ->setCustomerReference($customerReference);

        $availabilityNotificationSubscriptionResponseTransfer = $this->availabilityNotificationClient->subscribe($availabilityNotificationSubscriptionTransfer);

        if (!$availabilityNotificationSubscriptionResponseTransfer->getIsSuccess()) {
            return $this->availabilityNotificationsRestResponseBuilder->createSubscribeErrorResponse($availabilityNotificationSubscriptionResponseTransfer);
        }

        return $this->availabilityNotificationsRestResponseBuilder->createAvailabilityNotificationResponse($availabilityNotificationSubscriptionResponseTransfer->getAvailabilityNotificationSubscriptionOrFail());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function unsubscribeBySubscriptionKey(RestRequestInterface $restRequest): RestResponseInterface
    {
        $availabilityNotificationSubscriptionResponseTransfer = $this->availabilityNotificationClient->unsubscribeBySubscriptionKey((new AvailabilityNotificationSubscriptionTransfer())->setSubscriptionKey($restRequest->getResource()->getId()));

        if (!$availabilityNotificationSubscriptionResponseTransfer->getIsSuccess()) {
            return $this->availabilityNotificationsRestResponseBuilder->createUnsubscribeErrorResponse($availabilityNotificationSubscriptionResponseTransfer);
        }

        return $this->availabilityNotificationsRestResponseBuilder->createEmptyResponse();
    }
}
