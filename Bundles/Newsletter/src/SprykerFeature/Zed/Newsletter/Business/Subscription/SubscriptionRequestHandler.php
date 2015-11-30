<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer;
use SprykerFeature\Shared\Newsletter\Messages\Messages;
use SprykerFeature\Zed\Newsletter\Business\Exception\MissingNewsletterSubscriberEmailException;
use SprykerFeature\Zed\Newsletter\Persistence\NewsletterQueryContainer;

class SubscriptionRequestHandler
{

    /**
     * @var SubscriptionManagerInterface
     */
    protected $subscriptionManager;

    /**
     * @var SubscriberManagerInterface
     */
    protected $subscriberManager;

    /**
     * @var NewsletterQueryContainer
     */
    protected $queryContainer;

    /**
     * @var bool
     */
    private $subscriberExists;

    /**
     * @param SubscriptionManagerInterface $subscriptionManager
     * @param SubscriberManagerInterface $subscriberManager
     * @param NewsletterQueryContainer $queryContainer
     */
    public function __construct(
        SubscriptionManagerInterface $subscriptionManager,
        SubscriberManagerInterface $subscriberManager,
        NewsletterQueryContainer $queryContainer
    ) {
        $this->subscriptionManager = $subscriptionManager;
        $this->subscriberManager = $subscriberManager;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     * @param SubscriberOptInHandlerInterface $optInHandler
     *
     * @throws MissingNewsletterSubscriberEmailException
     * @throws \Exception
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function processNewsletterSubscriptions(
        NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest,
        SubscriberOptInHandlerInterface $optInHandler
    ) {
        $subscriptionResponse = $this->createSubscriptionResponse();

        $connection = $this->queryContainer->getConnection();
        $connection->beginTransaction();

        try {
            $newsletterSubscriberTransfer = $this->getNewsletterSubscriber($newsletterSubscriptionRequest->getNewsletterSubscriber());

            foreach ($newsletterSubscriptionRequest->getNewsletterTypes() as $newsletterTypeTransfer) {
                $subscriptionResult = $this->processSubscription($newsletterSubscriberTransfer, $newsletterTypeTransfer);
                $subscriptionResponse->addSubscriptionResult($subscriptionResult);
            }

            if ($this->subscriberExists === false) {
                $optInHandler->optIn($newsletterSubscriberTransfer);
            }

            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            throw $e;
        }

        return $subscriptionResponse;
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @throws MissingNewsletterSubscriberEmailException
     * @throws \Exception
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function processNewsletterUnsubscriptions(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        $subscriptionResponse = $this->createSubscriptionResponse();

        $connection = $this->queryContainer->getConnection();
        $connection->beginTransaction();

        try {
            $newsletterSubscriberTransfer = $newsletterSubscriptionRequest->getNewsletterSubscriber();

            foreach ($newsletterSubscriptionRequest->getNewsletterTypes() as $newsletterTypeTransfer) {
                $isSuccess = $this->subscriptionManager->unsubscribe($newsletterSubscriberTransfer, $newsletterTypeTransfer);

                $subscriptionResult = $this->createSubscriptionResultTransfer($newsletterTypeTransfer, $isSuccess);
                $subscriptionResponse->addSubscriptionResult($subscriptionResult);
            }

            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            throw $e;
        }

        return $subscriptionResponse;
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function checkNewsletterSubscriptions(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        $subscriptionResponse = $this->createSubscriptionResponse();

        $newsletterSubscriberTransfer = $newsletterSubscriptionRequest->getNewsletterSubscriber();

        foreach ($newsletterSubscriptionRequest->getNewsletterTypes() as $newsletterTypeTransfer) {
            $isAlreadySubscribed = $this->subscriptionManager->isAlreadySubscribed($newsletterSubscriberTransfer, $newsletterTypeTransfer);

            if ($isAlreadySubscribed) {
                $subscriptionResult = $this->createSubscriptionResultTransfer($newsletterTypeTransfer, true);
            } else {
                $subscriptionResult = $this->createSubscriptionResultTransfer($newsletterTypeTransfer, false);
            }

            $subscriptionResponse->addSubscriptionResult($subscriptionResult);
        }

        return $subscriptionResponse;
    }

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriberTransfer
     *
     * @throws MissingNewsletterSubscriberEmailException
     *
     * @return NewsletterSubscriberTransfer
     */
    protected function getNewsletterSubscriber(NewsletterSubscriberTransfer $newsletterSubscriberTransfer)
    {
        $email = $newsletterSubscriberTransfer->getEmail();
        if ($newsletterSubscriberTransfer->getEmail() === null) {
            throw new MissingNewsletterSubscriberEmailException('Missing newsletter subscriber email.');
        }

        $loadedNewsletterSubscriberTransfer = $this->subscriberManager->loadSubscriberByEmail($email);
        $this->subscriberExists = true;

        if ($loadedNewsletterSubscriberTransfer === null) {
            $loadedNewsletterSubscriberTransfer = $this->subscriberManager->createSubscriberFromTransfer($newsletterSubscriberTransfer);
            $this->subscriberExists = false;
        }

        return $loadedNewsletterSubscriberTransfer;
    }

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriberTransfer
     * @param NewsletterTypeTransfer $newsletterTypeTransfer
     *
     * @return NewsletterSubscriptionResultTransfer
     */
    protected function processSubscription(NewsletterSubscriberTransfer $newsletterSubscriberTransfer, NewsletterTypeTransfer $newsletterTypeTransfer)
    {
        $isAlreadySubscribed = $this->subscriptionManager->isAlreadySubscribed($newsletterSubscriberTransfer, $newsletterTypeTransfer);

        if ($isAlreadySubscribed) {
            $subscriptionResult = $this->createAlreadySubscribedResult($newsletterTypeTransfer);
        } else {
            $this->subscriptionManager->subscribe($newsletterSubscriberTransfer, $newsletterTypeTransfer);
            $subscriptionResult = $this->createSubscriptionResultTransfer($newsletterTypeTransfer, true);
        }

        return $subscriptionResult;
    }

    /**
     * @return NewsletterSubscriptionResponseTransfer
     */
    protected function createSubscriptionResponse()
    {
        $subscriptionResponse = new NewsletterSubscriptionResponseTransfer();

        return $subscriptionResponse;
    }

    /**
     * @param NewsletterTypeTransfer $newsletterTypeTransfer
     *
     * @return NewsletterSubscriptionResultTransfer
     */
    protected function createAlreadySubscribedResult(NewsletterTypeTransfer $newsletterTypeTransfer)
    {
        $subscriptionResultTransfer = $this->createSubscriptionResultTransfer($newsletterTypeTransfer, false);
        $subscriptionResultTransfer->setErrorMessage(Messages::ALREADY_SUBSCRIBED);

        return $subscriptionResultTransfer;
    }

    /**
     * @param NewsletterTypeTransfer $newsletterType
     * @param bool $isSuccess
     *
     * @return NewsletterSubscriptionResultTransfer
     */
    protected function createSubscriptionResultTransfer(NewsletterTypeTransfer $newsletterType, $isSuccess)
    {
        $subscriptionResultTransfer = new NewsletterSubscriptionResultTransfer();
        $subscriptionResultTransfer->setNewsletterType($newsletterType);
        $subscriptionResultTransfer->setIsSuccess($isSuccess);

        return $subscriptionResultTransfer;
    }

    /**
     * @param NewsletterSubscriberTransfer $subscriber
     *
     * @return void
     */
    public function assignCustomerToExistingSubscriber(NewsletterSubscriberTransfer $subscriber)
    {
        $this->subscriberManager->assignCustomerToExistingSubscriber($subscriber);
    }

}
