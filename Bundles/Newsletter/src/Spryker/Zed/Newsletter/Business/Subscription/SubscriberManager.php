<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainer;
use Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriber;

class SubscriberManager implements SubscriberManagerInterface
{

    /**
     * @var NewsletterQueryContainer
     */
    protected $queryContainer;

    /**
     * @var SubscriberKeyGeneratorInterface
     */
    protected $subscriberKeyGenerator;

    /**
     * @param NewsletterQueryContainer $queryContainer
     * @param SubscriberKeyGeneratorInterface $subscriberKeyGenerator
     */
    public function __construct(NewsletterQueryContainer $queryContainer, SubscriberKeyGeneratorInterface $subscriberKeyGenerator)
    {
        $this->queryContainer = $queryContainer;
        $this->subscriberKeyGenerator = $subscriberKeyGenerator;
    }

    /**
     * @param string $email
     *
     * @return NewsletterSubscriberTransfer|null
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
     * @param NewsletterSubscriberTransfer $newsletterSubscriberTransfer
     *
     * @return NewsletterSubscriberTransfer
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
     * @param NewsletterSubscriberTransfer $subscriber
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
     * @param SpyNewsletterSubscriber $subscriberEntity
     *
     * @return NewsletterSubscriberTransfer
     */
    protected function convertSubscriberEntityToTransfer(SpyNewsletterSubscriber $subscriberEntity)
    {
        $subscriberTransfer = new NewsletterSubscriberTransfer();
        $subscriberTransfer->fromArray($subscriberEntity->toArray(), true);

        return $subscriberTransfer;
    }

}
