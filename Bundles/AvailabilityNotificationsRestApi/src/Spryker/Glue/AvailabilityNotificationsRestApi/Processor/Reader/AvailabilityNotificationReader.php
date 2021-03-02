<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Reader;

use Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface;
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
    protected $restResponseBuilder;

    /**
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface $availabilityNotificationClient
     * @param \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface $restResponseBuilder
     */
    public function __construct(
        AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface $availabilityNotificationClient,
        AvailabilityNotificationsRestResponseBuilderInterface $restResponseBuilder
    )
    {
        $this->availabilityNotificationClient = $availabilityNotificationClient;
        $this->restResponseBuilder = $restResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAvailabilityNotifications(RestRequestInterface $restRequest): RestResponseInterface
    {
        $availabilityNotificationCriteriaTransfer = new AvailabilityNotificationCriteriaTransfer();
        $availabilityNotificationCriteriaTransfer->setCustomerReferences([$restRequest->getRestUser()->getNaturalIdentifier()]);

        if ($restRequest->getPage() !== null) {
            $availabilityNotificationCriteriaTransfer->setPagination(
                (new PaginationTransfer())
                    ->setMaxPerPage($restRequest->getPage()->getLimit())
                    ->setPage(($restRequest->getPage()->getOffset() / $restRequest->getPage()->getLimit()) + 1)
            );
        }

        $availabilityNotificationSubscriptionCollectionTransfer = $this->availabilityNotificationClient->getByCustomerAction($availabilityNotificationCriteriaTransfer);

        return $this->restResponseBuilder->createAvailabilityNotificationCollectionResponse($availabilityNotificationSubscriptionCollectionTransfer);
    }
}
