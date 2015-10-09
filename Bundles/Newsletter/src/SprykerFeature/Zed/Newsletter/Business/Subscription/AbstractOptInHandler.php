<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use SprykerFeature\Zed\Newsletter\Persistence\NewsletterQueryContainer;
use SprykerFeature\Zed\Newsletter\Persistence\Propel\SpyNewsletterSubscriber;

abstract class AbstractOptInHandler
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
     * @var SubscriberOptInSenderInterface[]
     */
    protected $subscriberOptInSenders;

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
     * @param NewsletterSubscriberInterface $subscriberTransfer
     *
     * @return \SprykerFeature\Zed\Newsletter\Persistence\Propel\SpyNewsletterSubscriber
     */
    protected function findSubscriberEntity(NewsletterSubscriberInterface $subscriberTransfer)
    {
        $subscriberQuery = $this->queryContainer->querySubscriber();

        $idNewsletterSubscriber = $subscriberTransfer->getIdNewsletterSubscriber();
        if (null !== $idNewsletterSubscriber) {
            return $subscriberQuery->findOneByIdNewsletterSubscriber($idNewsletterSubscriber);
        }

        $email = $subscriberTransfer->getEmail();
        if (null !== $email) {
            return $subscriberQuery->findOneByEmail($email);
        }

        return null;
    }

    /**
     * @param SpyNewsletterSubscriber $subscriberEntity
     */
    protected function setSubscriberKey(SpyNewsletterSubscriber $subscriberEntity)
    {
        $subscriberKey = $this->subscriberKeyGenerator->generateKey();
        $subscriberEntity->setSubscriberKey($subscriberKey);
        $subscriberEntity->save();
    }
}
