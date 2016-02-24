<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriber;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainer;

class SubscriberManager implements SubscriberManagerInterface
{

    /**
     * @var \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainer
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Newsletter\Business\Subscription\SubscriberKeyGeneratorInterface
     */
    protected $subscriberKeyGenerator;

    /**
     * @param \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainer $queryContainer
     * @param \Spryker\Zed\Newsletter\Business\Subscription\SubscriberKeyGeneratorInterface $subscriberKeyGenerator
     */
    public function __construct(NewsletterQueryContainer $queryContainer, SubscriberKeyGeneratorInterface $subscriberKeyGenerator)
    {
        $this->queryContainer = $queryContainer;
        $this->subscriberKeyGenerator = $subscriberKeyGenerator;
    }

    /**
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriberTransfer|null
     */
    public function loadSubscriberByEmail($email)
    {
        $subscriberEntity = $this->queryContainer->querySubscriber()
            ->filterByEmail($email)
            ->findOne();

        if ($subscriberEntity === null) {
            return null;
        }

        return $this->convertSubscriberEntityToTransfer($subscriberEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriberTransfer
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriberTransfer
     */
    public function createSubscriberFromTransfer(NewsletterSubscriberTransfer $newsletterSubscriberTransfer)
    {
        $subscriberEntity = new SpyNewsletterSubscriber();
        $subscriberEntity->fromArray($newsletterSubscriberTransfer->toArray());

        if ($subscriberEntity->getSubscriberKey() === null) {
            $subscriberKey = $this->subscriberKeyGenerator->generateKey();
            $subscriberEntity->setSubscriberKey($subscriberKey);
        }

        $subscriberEntity->save();

        return $this->convertSubscriberEntityToTransfer($subscriberEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $subscriber
     *
     * @return void
     */
    public function assignCustomerToExistingSubscriber(NewsletterSubscriberTransfer $subscriber)
    {
        if ($subscriber->getFkCustomer() === null) {
            return;
        }

        $subscriberEntity = $this->queryContainer->querySubscriber()
            ->findOneByEmail($subscriber->getEmail());

        if ($subscriberEntity !== null) {
            $subscriberEntity->setFkCustomer($subscriber->getFkCustomer());
            $subscriberEntity->save();
        }
    }

    /**
     * @param \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriber $subscriberEntity
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriberTransfer
     */
    protected function convertSubscriberEntityToTransfer(SpyNewsletterSubscriber $subscriberEntity)
    {
        $subscriberTransfer = new NewsletterSubscriberTransfer();
        $subscriberTransfer->fromArray($subscriberEntity->toArray(), true);

        return $subscriberTransfer;
    }

}
