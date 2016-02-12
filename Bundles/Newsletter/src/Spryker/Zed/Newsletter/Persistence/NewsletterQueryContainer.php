<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Newsletter\Persistence\NewsletterPersistenceFactory getFactory()
 */
class NewsletterQueryContainer extends AbstractQueryContainer implements NewsletterQueryContainerInterface
{

    /**
     * @param string $email
     * @param string $newsletterTypeName
     *
     * @return \Orm\Zed\Newsletter\Persistence\Base\SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionByEmailAndNewsletterTypeName($email, $newsletterTypeName)
    {
        $subscriptionQuery = $this->querySubscription()
            ->joinSpyNewsletterType()
            ->useSpyNewsletterTypeQuery()
                ->filterByName($newsletterTypeName)
            ->endUse()
            ->joinSpyNewsletterSubscriber()
            ->useSpyNewsletterSubscriberQuery()
                ->filterByEmail($email)
            ->endUse();

        return $subscriptionQuery;
    }

    /**
     * @param string $subscriberKey
     * @param string $newsletterTypeName
     *
     * @return \Orm\Zed\Newsletter\Persistence\Base\SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionBySubscriberKeyAndNewsletterTypeName($subscriberKey, $newsletterTypeName)
    {
        $subscriptionQuery = $this->querySubscription()
            ->joinSpyNewsletterType()
            ->useSpyNewsletterTypeQuery()
                ->filterByName($newsletterTypeName)
            ->endUse()
            ->joinSpyNewsletterSubscriber()
            ->useSpyNewsletterSubscriberQuery()
                ->filterBySubscriberKey($subscriberKey)
            ->endUse();

        return $subscriptionQuery;
    }

    /**
     * @param int $idCustomer
     * @param string $newsletterTypeName
     *
     * @return \Orm\Zed\Newsletter\Persistence\Base\SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionByIdCustomerAndNewsletterTypeName($idCustomer, $newsletterTypeName)
    {
        $subscriptionQuery = $this->querySubscription()
            ->joinSpyNewsletterType()
            ->useSpyNewsletterTypeQuery()
                ->filterByName($newsletterTypeName)
            ->endUse()
            ->joinSpyNewsletterSubscriber()
            ->useSpyNewsletterSubscriberQuery()
                ->filterByFkCustomer($idCustomer)
            ->endUse();

        return $subscriptionQuery;
    }

    /**
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriberQuery
     */
    public function querySubscriber()
    {
        return $this->getFactory()->createNewsletterSubscriberQuery();
    }

    /**
     * @return \Orm\Zed\Newsletter\Persistence\Base\SpyNewsletterSubscriptionQuery
     */
    public function querySubscription()
    {
        return $this->getFactory()->createNewsletterSubscriptionQuery();
    }

    /**
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterTypeQuery
     */
    public function queryNewsletterType()
    {
        return $this->getFactory()->createNewsletterTypeQuery();
    }

}
