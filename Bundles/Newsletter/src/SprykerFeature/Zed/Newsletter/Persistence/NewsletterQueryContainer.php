<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Newsletter\Persistence\Propel\Base\SpyNewsletterSubscriptionQuery;
use SprykerFeature\Zed\Newsletter\Persistence\Propel\SpyNewsletterSubscriberQuery;

class NewsletterQueryContainer extends AbstractQueryContainer
{
    /**
     * @param string $email
     * @param int $idNewsletterType
     *
     * @return SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionByEmailAndNewsletterType($email, $idNewsletterType)
    {
        $subscriptionQuery = $this->querySubscription()
            ->joinSpyNewsletterType()
            ->useSpyNewsletterTypeQuery()
                ->filterByIdNewsletterType($idNewsletterType)
            ->endUse()
            ->joinSpyNewsletterSubscriber()
            ->useSpyNewsletterSubscriberQuery()
                ->filterByEmail($email)
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
}
