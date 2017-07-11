<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscription;
use Spryker\Zed\Newsletter\Business\Exception\MissingNewsletterTypeException;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface;

class SubscriptionManager implements SubscriptionManagerInterface
{

    /**
     * @var \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface $queryContainer
     */
    public function __construct(NewsletterQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     * @param \Generated\Shared\Transfer\NewsletterTypeTransfer $newsletterType
     *
     * @return void
     */
    public function subscribe(NewsletterSubscriberTransfer $newsletterSubscriber, NewsletterTypeTransfer $newsletterType)
    {
        $newsletterSubscriber->requireIdNewsletterSubscriber();

        $subscriptionEntity = new SpyNewsletterSubscription();
        $subscriptionEntity->setFkNewsletterSubscriber($newsletterSubscriber->getIdNewsletterSubscriber());
        $subscriptionEntity->setFkNewsletterType($this->getIdNewsletterType($newsletterType));
        $subscriptionEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     * @param \Generated\Shared\Transfer\NewsletterTypeTransfer $newsletterType
     *
     * @return bool
     */
    public function isAlreadySubscribed(NewsletterSubscriberTransfer $newsletterSubscriber, NewsletterTypeTransfer $newsletterType)
    {
        $newsletterSubscriber->requireEmail();
        $newsletterType->requireName();

        $subscriptionCount = $this->queryContainer
            ->querySubscriptionByEmailAndNewsletterTypeName($newsletterSubscriber->getEmail(), $newsletterType->getName())
            ->count();

        return $subscriptionCount > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     * @param \Generated\Shared\Transfer\NewsletterTypeTransfer $newsletterType
     *
     * @return bool
     */
    public function unsubscribe(NewsletterSubscriberTransfer $newsletterSubscriber, NewsletterTypeTransfer $newsletterType)
    {
        $subscriptionEntity = $this->getSubscription($newsletterSubscriber, $newsletterType);

        if ($subscriptionEntity !== null) {
            $subscriptionEntity->delete();

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterTypeTransfer $newsletterType
     *
     * @throws \Spryker\Zed\Newsletter\Business\Exception\MissingNewsletterTypeException
     *
     * @return int
     */
    protected function getIdNewsletterType(NewsletterTypeTransfer $newsletterType)
    {
        if ($newsletterType->getIdNewsletterType() !== null) {
            return $newsletterType->getIdNewsletterType();
        }

        $newsletterTypeEntity = $this->queryContainer
            ->queryNewsletterType()
            ->findOneByName($newsletterType->getName());

        if ($newsletterTypeEntity !== null) {
            return $newsletterTypeEntity->getIdNewsletterType();
        }

        throw new MissingNewsletterTypeException(sprintf('Newsletter type "%s" not found.', $newsletterType->getName()));
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     * @param \Generated\Shared\Transfer\NewsletterTypeTransfer $newsletterType
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscription|null
     */
    protected function getSubscription(NewsletterSubscriberTransfer $newsletterSubscriber, NewsletterTypeTransfer $newsletterType)
    {
        if ($newsletterSubscriber->getSubscriberKey() !== null) {
            $subscriptionEntity = $this->queryContainer
                ->querySubscriptionBySubscriberKeyAndNewsletterTypeName($newsletterSubscriber->getSubscriberKey(), $newsletterType->getName())
                ->findOne();

            return $subscriptionEntity;
        }

        if ($newsletterSubscriber->getEmail() !== null) {
            $subscriptionEntity = $this->queryContainer
                ->querySubscriptionByEmailAndNewsletterTypeName($newsletterSubscriber->getEmail(), $newsletterType->getName())
                ->findOne();

            return $subscriptionEntity;
        }

        if ($newsletterSubscriber->getFkCustomer() !== null) {
            $subscriptionEntity = $this->queryContainer
                ->querySubscriptionByIdCustomerAndNewsletterTypeName($newsletterSubscriber->getFkCustomer(), $newsletterType->getName())
                ->findOne();

            return $subscriptionEntity;
        }

        return null;
    }

}
