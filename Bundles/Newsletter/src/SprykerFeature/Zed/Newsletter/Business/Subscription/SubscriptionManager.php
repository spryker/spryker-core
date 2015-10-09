<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use Generated\Shared\Newsletter\NewsletterTypeInterface;
use SprykerFeature\Zed\Newsletter\Persistence\NewsletterQueryContainer;
use SprykerFeature\Zed\Newsletter\Persistence\Propel\SpyNewsletterSubscription;

/**
 * TODO: create demo data
 * TODO: double opt-in (email, yves controller, hash)
 * TODO: unsubscribe
 * TODO: subscription logic
 * TODO: remove customer.has_newsletter_subscription field
 */
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
        $subscriptionEntity->setFkNewsletterType($newsletterType->getIdNewsletterType());
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
            ->querySubscriptionByEmailAndNewsletterType($newsletterSubscriber->getEmail(), $newsletterType->getIdNewsletterType())
            ->count();

        return $subscriptionCount > 0;
    }
}
