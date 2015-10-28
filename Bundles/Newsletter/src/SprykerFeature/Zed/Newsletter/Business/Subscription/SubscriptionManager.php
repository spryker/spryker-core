<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use Generated\Shared\Newsletter\NewsletterTypeInterface;
use SprykerFeature\Zed\Newsletter\Business\Exception\MissingNewsletterTypeException;
use SprykerFeature\Zed\Newsletter\Persistence\NewsletterQueryContainer;
use Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscription;

class SubscriptionManager implements SubscriptionManagerInterface
{

    /**
     * @var NewsletterQueryContainer
     */
    protected $queryContainer;

    /**
     * @param NewsletterQueryContainer $queryContainer
     */
    public function __construct(NewsletterQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     * @param NewsletterTypeInterface $newsletterType
     */
    public function subscribe(NewsletterSubscriberInterface $newsletterSubscriber, NewsletterTypeInterface $newsletterType)
    {
        $subscriptionEntity = new SpyNewsletterSubscription();
        $subscriptionEntity->setFkNewsletterSubscriber($newsletterSubscriber->getIdNewsletterSubscriber());
        $subscriptionEntity->setFkNewsletterType($this->getIdNewsletterType($newsletterType));
        $subscriptionEntity->save();
    }

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     * @param NewsletterTypeInterface $newsletterType
     *
     * @return bool
     */
    public function isAlreadySubscribed(NewsletterSubscriberInterface $newsletterSubscriber, NewsletterTypeInterface $newsletterType)
    {
        $subscriptionCount = $this->queryContainer
            ->querySubscriptionByEmailAndNewsletterTypeName($newsletterSubscriber->getEmail(), $newsletterType->getName())
            ->count();

        return $subscriptionCount > 0;
    }

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     * @param NewsletterTypeInterface $newsletterType
     *
     * @return bool
     */
    public function unsubscribe(NewsletterSubscriberInterface $newsletterSubscriber, NewsletterTypeInterface $newsletterType)
    {
        $subscriptionEntity = $this->getSubscription($newsletterSubscriber, $newsletterType);

        if (null !== $subscriptionEntity) {
            $subscriptionEntity->delete();

            return true;
        }

        return false;
    }

    /**
     * @param NewsletterTypeInterface $newsletterType
     *
     * @throws MissingNewsletterTypeException
     *
     * @return int
     */
    protected function getIdNewsletterType(NewsletterTypeInterface $newsletterType)
    {
        if (null !== $newsletterType->getIdNewsletterType()) {
            return $newsletterType->getIdNewsletterType();
        }

        $newsletterTypeEntity = $this->queryContainer
            ->queryNewsletterType()
            ->findOneByName($newsletterType->getName())
        ;

        if (null !== $newsletterTypeEntity) {
            return $newsletterTypeEntity->getIdNewsletterType();
        }

        throw new MissingNewsletterTypeException(sprintf('Newsletter type "%s" not found.', $newsletterType->getName()));
    }

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     * @param NewsletterTypeInterface $newsletterType
     *
     * @return SpyNewsletterSubscription|null
     */
    protected function getSubscription(NewsletterSubscriberInterface $newsletterSubscriber, NewsletterTypeInterface $newsletterType)
    {
        if (null !== $newsletterSubscriber->getSubscriberKey()) {
            $subscriptionEntity = $this->queryContainer
                ->querySubscriptionBySubscriberKeyAndNewsletterTypeName($newsletterSubscriber->getSubscriberKey(), $newsletterType->getName())
                ->findOne()
            ;

            return $subscriptionEntity;
        }

        if (null !== $newsletterSubscriber->getFkCustomer()) {
            $subscriptionEntity = $this->queryContainer
                ->querySubscriptionByIdCustomerAndNewsletterTypeName($newsletterSubscriber->getFkCustomer(), $newsletterType->getName())
                ->findOne()
            ;

            return $subscriptionEntity;
        }

        return null;
    }

}
