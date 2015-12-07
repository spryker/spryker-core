<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
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
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     * @param NewsletterTypeTransfer $newsletterType
     *
     * @return void
     */
    public function subscribe(NewsletterSubscriberTransfer $newsletterSubscriber, NewsletterTypeTransfer $newsletterType)
    {
        $subscriptionEntity = new SpyNewsletterSubscription();
        $subscriptionEntity->setFkNewsletterSubscriber($newsletterSubscriber->getIdNewsletterSubscriber());
        $subscriptionEntity->setFkNewsletterType($this->getIdNewsletterType($newsletterType));
        $subscriptionEntity->save();
    }

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     * @param NewsletterTypeTransfer $newsletterType
     *
     * @return bool
     */
    public function isAlreadySubscribed(NewsletterSubscriberTransfer $newsletterSubscriber, NewsletterTypeTransfer $newsletterType)
    {
        $subscriptionCount = $this->queryContainer
            ->querySubscriptionByEmailAndNewsletterTypeName($newsletterSubscriber->getEmail(), $newsletterType->getName())
            ->count();

        return $subscriptionCount > 0;
    }

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     * @param NewsletterTypeTransfer $newsletterType
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
     * @param NewsletterTypeTransfer $newsletterType
     *
     * @throws MissingNewsletterTypeException
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
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     * @param NewsletterTypeTransfer $newsletterType
     *
     * @return SpyNewsletterSubscription|null
     */
    protected function getSubscription(NewsletterSubscriberTransfer $newsletterSubscriber, NewsletterTypeTransfer $newsletterType)
    {
        if ($newsletterSubscriber->getSubscriberKey() !== null) {
            $subscriptionEntity = $this->queryContainer
                ->querySubscriptionBySubscriberKeyAndNewsletterTypeName($newsletterSubscriber->getSubscriberKey(), $newsletterType->getName())
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
