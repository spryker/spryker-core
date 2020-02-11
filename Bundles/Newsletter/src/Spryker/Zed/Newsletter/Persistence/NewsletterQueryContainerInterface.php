<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface NewsletterQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param string $email
     * @param string $newsletterTypeName
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionByEmailAndNewsletterTypeName($email, $newsletterTypeName);

    /**
     * @api
     *
     * @param string $subscriberKey
     * @param string $newsletterTypeName
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionBySubscriberKeyAndNewsletterTypeName($subscriberKey, $newsletterTypeName);

    /**
     * @api
     *
     * @param int $idCustomer
     * @param string $newsletterTypeName
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionByIdCustomerAndNewsletterTypeName($idCustomer, $newsletterTypeName);

    /**
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriberQuery
     */
    public function querySubscriberByIdCustomer($idCustomer);

    /**
     * @api
     *
     * @param string $email
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriberQuery
     */
    public function querySubscriberByEmail($email);

    /**
     * @api
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriberQuery
     */
    public function querySubscriber();

    /**
     * @api
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery
     */
    public function querySubscription();

    /**
     * @api
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterTypeQuery
     */
    public function queryNewsletterType();
}
