<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;

interface SubscriberManagerInterface
{

    /**
     * @param string $email
     *
     * @return NewsletterSubscriberInterface
     */
    public function loadSubscriberByEmail($email);

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriberTransfer
     *
     * @return NewsletterSubscriberInterface
     */
    public function createSubscriberFromTransfer(NewsletterSubscriberInterface $newsletterSubscriberTransfer);

    /**
     * @param NewsletterSubscriberInterface $subscriber
     */
    public function assignCustomerWithExistingSubscriber(NewsletterSubscriberInterface $subscriber);

}
