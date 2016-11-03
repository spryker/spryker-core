<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Spryker\Shared\Newsletter\Messages\Messages;
use Spryker\Zed\Newsletter\Business\Exception\MissingNewsletterSubscriberEmailException;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface;

class SubscriptionRequestHandler
{

    /**
     * @var \Spryker\Zed\Newsletter\Business\Subscription\SubscriptionManagerInterface
     */
    protected $subscriptionManager;

    /**
     * @var \Spryker\Zed\Newsletter\Business\Subscription\SubscriberManagerInterface
     */
    protected $subscriberManager;

    /**
     * @var \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var bool
     */
    private $subscriberExists;

    /**
     * @param \Spryker\Zed\Newsletter\Business\Subscription\SubscriptionManagerInterface $subscriptionManager
     * @param \Spryker\Zed\Newsletter\Business\Subscription\SubscriberManagerInterface $subscriberManager
     * @param \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface $queryContainer
     */
    public function __construct(
        SubscriptionManagerInterface $subscriptionManager,
        SubscriberManagerInterface $subscriberManager,
        NewsletterQueryContainerInterface $queryContainer
    ) {
        $this->subscriptionManager = $subscriptionManager;
        $this->subscriberManager = $subscriberManager;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     * @param \Spryker\Zed\Newsletter\Business\Subscription\SubscriberOptInHandlerInterface $optInHandler
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
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
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
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
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
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
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriberTransfer
     *
     * @throws \Spryker\Zed\Newsletter\Business\Exception\MissingNewsletterSubscriberEmailException
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriberTransfer
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
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriberTransfer
     * @param \Generated\Shared\Transfer\NewsletterTypeTransfer $newsletterTypeTransfer
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer
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
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    protected function createSubscriptionResponse()
    {
        $subscriptionResponse = new NewsletterSubscriptionResponseTransfer();

        return $subscriptionResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterTypeTransfer $newsletterTypeTransfer
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer
     */
    protected function createAlreadySubscribedResult(NewsletterTypeTransfer $newsletterTypeTransfer)
    {
        $subscriptionResultTransfer = $this->createSubscriptionResultTransfer($newsletterTypeTransfer, false);
        $subscriptionResultTransfer->setErrorMessage(Messages::ALREADY_SUBSCRIBED);

        return $subscriptionResultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterTypeTransfer $newsletterType
     * @param bool $isSuccess
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer
     */
    protected function createSubscriptionResultTransfer(NewsletterTypeTransfer $newsletterType, $isSuccess)
    {
        $subscriptionResultTransfer = new NewsletterSubscriptionResultTransfer();
        $subscriptionResultTransfer->setNewsletterType($newsletterType);
        $subscriptionResultTransfer->setIsSuccess($isSuccess);

        return $subscriptionResultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $subscriber
     *
     * @return bool
     */
    public function assignCustomerToExistingSubscriber(NewsletterSubscriberTransfer $subscriber)
    {
        return $this->subscriberManager->assignCustomerToExistingSubscriber($subscriber);
    }

}
