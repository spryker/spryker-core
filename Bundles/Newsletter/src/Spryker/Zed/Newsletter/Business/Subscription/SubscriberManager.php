<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriber;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface;

class SubscriberManager implements SubscriberManagerInterface
{

    /**
     * @var \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Newsletter\Business\Subscription\SubscriberKeyGeneratorInterface
     */
    protected $subscriberKeyGenerator;

    /**
     * @param \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Newsletter\Business\Subscription\SubscriberKeyGeneratorInterface $subscriberKeyGenerator
     */
    public function __construct(NewsletterQueryContainerInterface $queryContainer, SubscriberKeyGeneratorInterface $subscriberKeyGenerator)
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
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriberTransfer
     *
     * @return void
     */
    public function assignCustomerToExistingSubscriber(NewsletterSubscriberTransfer $newsletterSubscriberTransfer)
    {
        $newsletterSubscriberTransfer->requireFkCustomer();

        $subscriberEntity = $this->queryContainer->querySubscriber()
            ->findOneByEmail($newsletterSubscriberTransfer->getEmail());
        if ($subscriberEntity === null) {
            return;
        }

        $subscriberEntity->setFkCustomer($newsletterSubscriberTransfer->getFkCustomer());
        $subscriberEntity->save();
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
