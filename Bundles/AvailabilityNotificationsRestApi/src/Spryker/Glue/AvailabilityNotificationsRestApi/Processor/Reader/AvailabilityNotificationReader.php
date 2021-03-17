<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Reader;

use Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToStoreClientInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Validator\AvailabilityNotificationsRestApiValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class AvailabilityNotificationReader implements AvailabilityNotificationReaderInterface
{
    /**
     * @var \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface
     */
    protected $availabilityNotificationClient;

    /**
     * @var \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface
     */
    protected $availabilityNotificationsRestResponseBuilder;

    /**
     * @var \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Validator\AvailabilityNotificationsRestApiValidatorInterface
     */
    protected $restApiValidator;

    /**
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface $availabilityNotificationClient
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface $availabilityNotificationsRestResponseBuilder
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToStoreClientInterface $storeClient
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Validator\AvailabilityNotificationsRestApiValidatorInterface $restApiValidator
     */
    public function __construct(
        AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface $availabilityNotificationClient,
        AvailabilityNotificationsRestResponseBuilderInterface $availabilityNotificationsRestResponseBuilder,
        AvailabilityNotificationsRestApiToStoreClientInterface $storeClient,
        AvailabilityNotificationsRestApiValidatorInterface $restApiValidator
    ) {
        $this->availabilityNotificationClient = $availabilityNotificationClient;
        $this->availabilityNotificationsRestResponseBuilder = $availabilityNotificationsRestResponseBuilder;
        $this->storeClient = $storeClient;
        $this->restApiValidator = $restApiValidator;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getMyAvailabilityNotifications(RestRequestInterface $restRequest): RestResponseInterface
    {
        $availabilityNotificationCriteriaTransfer = new AvailabilityNotificationCriteriaTransfer();
        /**
         * @var \Generated\Shared\Transfer\RestUserTransfer $restUser
         */
        $restUser = $restRequest->getRestUser();
        $availabilityNotificationCriteriaTransfer->addCustomerReference($restUser->getNaturalIdentifierOrFail());
        $storeName = $this->storeClient->getCurrentStore()->getName();
        if ($storeName) {
            $availabilityNotificationCriteriaTransfer->addStoreName($storeName);
        }

        if ($restRequest->getPage() !== null) {
            $availabilityNotificationCriteriaTransfer->setPagination(
                (new PaginationTransfer())
                    ->setMaxPerPage($restRequest->getPage()->getLimit())
                    ->setPage(($restRequest->getPage()->getOffset() / $restRequest->getPage()->getLimit()) + 1)
            );
        }

        $availabilityNotificationSubscriptionCollectionTransfer = $this->availabilityNotificationClient->getAvailabilityNotifications($availabilityNotificationCriteriaTransfer);

        return $this->availabilityNotificationsRestResponseBuilder->createAvailabilityNotificationCollectionResponse($availabilityNotificationSubscriptionCollectionTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerAvailabilityNotifications(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$this->restApiValidator->isSameCustomerReference($restRequest)) {
            return $this->availabilityNotificationsRestResponseBuilder->createCustomerUnauthorizedErrorResponse();
        }

        return $this->getMyAvailabilityNotifications($restRequest);
    }
}
