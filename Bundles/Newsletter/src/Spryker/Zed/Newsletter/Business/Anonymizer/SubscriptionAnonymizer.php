<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Anonymizer;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Orm\Zed\Newsletter\Persistence\Base\SpyNewsletterSubscriber;
use Spryker\Zed\Newsletter\Business\Subscription\SubscriptionRequestHandlerInterface;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface;

class SubscriptionAnonymizer implements SubscriptionAnonymizerInterface
{
    /**
     * @var \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Newsletter\Business\Subscription\SubscriptionRequestHandlerInterface
     */
    protected $requestHandler;

    /**
     * @param \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Newsletter\Business\Subscription\SubscriptionRequestHandlerInterface $subscriptionRequestHandler
     */
    public function __construct(NewsletterQueryContainerInterface $queryContainer, SubscriptionRequestHandlerInterface $subscriptionRequestHandler)
    {
        $this->queryContainer = $queryContainer;
        $this->requestHandler = $subscriptionRequestHandler;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer
     *
     * @return void
     */
    public function process(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer)
    {
        $this->requestHandler->processNewsletterUnsubscriptions($newsletterSubscriptionRequestTransfer);

        $newsletterSubscriberTransfer = $newsletterSubscriptionRequestTransfer->getNewsletterSubscriber();

        $spyNewsletterSubscriber = $this->findSubscriber($newsletterSubscriberTransfer);

        if ($spyNewsletterSubscriber) {
            $spyNewsletterSubscriber = $this->anonymizeSubscriber($spyNewsletterSubscriber);
            $spyNewsletterSubscriber->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriberTransfer
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriber|null
     */
    protected function findSubscriber(NewsletterSubscriberTransfer $newsletterSubscriberTransfer)
    {
        return $this->queryContainer
            ->querySubscriberByIdCustomer($newsletterSubscriberTransfer->getFkCustomer())
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriber $spyNewsletterSubscriber
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriber
     */
    protected function anonymizeSubscriber(SpyNewsletterSubscriber $spyNewsletterSubscriber)
    {
        do {
            $randomEmail = md5((string)mt_rand());
        } while ($this->queryContainer->querySubscriberByEmail($randomEmail)->exists());

        $spyNewsletterSubscriber->setEmail($randomEmail);

        return $spyNewsletterSubscriber;
    }
}
