<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Persistence;

interface NewsletterQueryContainerInterface
{

    /**
     * @param string $email
     * @param string $newsletterTypeName
     *
     * @return \Orm\Zed\Newsletter\Persistence\Base\SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionByEmailAndNewsletterTypeName($email, $newsletterTypeName);

    /**
     * @param string $subscriberKey
     * @param string $newsletterTypeName
     *
     * @return \Orm\Zed\Newsletter\Persistence\Base\SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionBySubscriberKeyAndNewsletterTypeName($subscriberKey, $newsletterTypeName);

    /**
     * @param int $idCustomer
     * @param string $newsletterTypeName
     *
     * @return \Orm\Zed\Newsletter\Persistence\Base\SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionByIdCustomerAndNewsletterTypeName($idCustomer, $newsletterTypeName);

    /**
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriberQuery
     */
    public function querySubscriber();

    /**
     * @return \Orm\Zed\Newsletter\Persistence\Base\SpyNewsletterSubscriptionQuery
     */
    public function querySubscription();

    /**
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterTypeQuery
     */
    public function queryNewsletterType();

}
