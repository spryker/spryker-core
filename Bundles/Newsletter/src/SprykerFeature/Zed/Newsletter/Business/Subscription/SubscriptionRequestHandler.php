<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionRequestInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionResponseInterface;
use Generated\Shared\Newsletter\NewsletterTypeInterface;
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
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     * @param SubscriberOptInHandlerInterface $optInHandler
     *
     * @throws MissingNewsletterSubscriberEmailException
     * @throws \Exception
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function processNewsletterSubscriptions(
        NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest,
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

            if (false === $this->subscriberExists) {
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
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @throws MissingNewsletterSubscriberEmailException
     * @throws \Exception
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function processNewsletterUnsubscriptions(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
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
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function checkNewsletterSubscriptions(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
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
     * @param NewsletterSubscriberInterface $newsletterSubscriberTransfer
     *
     * @throws MissingNewsletterSubscriberEmailException
     *
     * @return NewsletterSubscriberInterface
     */
    protected function getNewsletterSubscriber(NewsletterSubscriberInterface $newsletterSubscriberTransfer)
    {
        $email = $newsletterSubscriberTransfer->getEmail();
        if (null === $newsletterSubscriberTransfer->getEmail()) {
            throw new MissingNewsletterSubscriberEmailException('Missing newsletter subscriber email.');
        }

        $loadedNewsletterSubscriberTransfer = $this->subscriberManager->loadSubscriberByEmail($email);
        $this->subscriberExists = true;

        if (null === $loadedNewsletterSubscriberTransfer) {
            $loadedNewsletterSubscriberTransfer = $this->subscriberManager->createSubscriberFromTransfer($newsletterSubscriberTransfer);
            $this->subscriberExists = false;
        }

        return $loadedNewsletterSubscriberTransfer;
    }

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriberTransfer
     * @param NewsletterTypeInterface $newsletterTypeTransfer
     *
     * @return NewsletterSubscriptionResultTransfer
     */
    protected function processSubscription(NewsletterSubscriberInterface $newsletterSubscriberTransfer, NewsletterTypeInterface $newsletterTypeTransfer)
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
     * @return NewsletterSubscriptionResponseInterface
     */
    protected function createSubscriptionResponse()
    {
        $subscriptionResponse = new NewsletterSubscriptionResponseTransfer();

        return $subscriptionResponse;
    }

    /**
     * @param NewsletterTypeInterface $newsletterTypeTransfer
     *
     * @return NewsletterSubscriptionResultTransfer
     */
    protected function createAlreadySubscribedResult(NewsletterTypeInterface $newsletterTypeTransfer)
    {
        $subscriptionResultTransfer = $this->createSubscriptionResultTransfer($newsletterTypeTransfer, false);
        $subscriptionResultTransfer->setErrorMessage(Messages::ALREADY_SUBSCRIBED);

        return $subscriptionResultTransfer;
    }

    /**
     * @param NewsletterTypeInterface $newsletterType
     * @param bool $isSuccess
     *
     * @return NewsletterSubscriptionResultTransfer
     */
    protected function createSubscriptionResultTransfer(NewsletterTypeInterface $newsletterType, $isSuccess)
    {
        $subscriptionResultTransfer = new NewsletterSubscriptionResultTransfer();
        $subscriptionResultTransfer->setNewsletterType($newsletterType);
        $subscriptionResultTransfer->setIsSuccess($isSuccess);

        return $subscriptionResultTransfer;
    }

    /**
     * @param NewsletterSubscriberInterface $subscriber
     *
     * @return void
     */
    public function assignCustomerWithExistingSubscriber(NewsletterSubscriberInterface $subscriber)
    {
        $this->subscriberManager->assignCustomerWithExistingSubscriber($subscriber);
    }

}
