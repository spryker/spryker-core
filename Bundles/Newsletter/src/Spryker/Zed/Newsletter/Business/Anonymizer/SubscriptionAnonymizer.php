<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Anonymizer;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Orm\Zed\Newsletter\Persistence\Base\SpyNewsletterSubscriber;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface;

class SubscriptionAnonymizer implements SubscriptionAnonymizerInterface
{

    /**
     * @var \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface
     */
    protected $queryContainer;

    public function __construct(NewsletterQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer
     *
     * @return bool
     */
    public function process(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer)
    {
        $newsletterSubscriberTransfer = $newsletterSubscriptionRequestTransfer->getNewsletterSubscriber();

        $spyNewsletterSubscriber = $this->getSubscriber($newsletterSubscriberTransfer);

        if ($spyNewsletterSubscriber) {
            $spyNewsletterSubscriber = $this->anonymizeSubscriber($spyNewsletterSubscriber);
            $spyNewsletterSubscriber->save();

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriberTransfer
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriber|null
     */
    protected function getSubscriber(NewsletterSubscriberTransfer $newsletterSubscriberTransfer)
    {
        return $this->queryContainer
            ->querySubscriberByIdCustomer($newsletterSubscriberTransfer->getFkCustomer())
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Newsletter\Persistence\Base\SpyNewsletterSubscriber $spyNewsletterSubscriber
     *
     * @return \Orm\Zed\Newsletter\Persistence\Base\SpyNewsletterSubscriber
     */
    protected function anonymizeSubscriber(SpyNewsletterSubscriber $spyNewsletterSubscriber)
    {
        do {
            $randomEmail = md5(mt_rand());
        } while ($this->queryContainer->querySubscriberByEmail($randomEmail)->exists());

        $spyNewsletterSubscriber->setEmail($randomEmail);

        return $spyNewsletterSubscriber;
    }

}
