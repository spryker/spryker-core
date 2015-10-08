<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Model;

use Generated\Shared\Newsletter\NewsletterSubscriptionInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionResponseInterface;
use Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer;

class SubscriptionManager implements SubscriptionManagerInterface
{
    /**
     * @param NewsletterSubscriptionInterface $newsletterSubscription
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribe(NewsletterSubscriptionInterface $newsletterSubscription)
    {
        $subscriptionResponse = $this->createSubscriptionResponse();

        return $subscriptionResponse;
    }

    /**
     * @param bool $isSuccess
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    protected function createSubscriptionResponse($isSuccess = true)
    {
        $subscriptionResponse = new NewsletterSubscriptionResponseTransfer();
        $subscriptionResponse->setIsSuccess($isSuccess);

        return $subscriptionResponse;
    }
}
