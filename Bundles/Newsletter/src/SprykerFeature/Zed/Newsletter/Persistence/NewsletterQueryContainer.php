<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Newsletter\Persistence\Propel\Base\SpyNewsletterSubscriptionQuery;
use SprykerFeature\Zed\Newsletter\Persistence\Propel\SpyNewsletterSubscriberQuery;
use SprykerFeature\Zed\Newsletter\Persistence\Propel\SpyNewsletterTypeQuery;

class NewsletterQueryContainer extends AbstractQueryContainer
{

    /**
     * @param string $email
     * @param string $newsletterTypeName
     *
     * @return SpyNewsletterSubscriptionQuery
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
            ->endUse()
        ;

        return $subscriptionQuery;
    }

    /**
     * @param string $subscriberKey
     * @param string $newsletterTypeName
     *
     * @return SpyNewsletterSubscriptionQuery
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
            ->endUse()
        ;

        return $subscriptionQuery;
    }

    /**
     * @return SpyNewsletterSubscriberQuery
     */
    public function querySubscriber()
    {
        return SpyNewsletterSubscriberQuery::create();
    }

    /**
     * @return SpyNewsletterSubscriptionQuery
     */
    public function querySubscription()
    {
        return SpyNewsletterSubscriptionQuery::create();
    }

    /**
     * @return SpyNewsletterTypeQuery
     */
    public function queryNewsletterType()
    {
        return SpyNewsletterTypeQuery::create();
    }

}
