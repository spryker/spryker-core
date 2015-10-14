<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use SprykerFeature\Zed\Newsletter\Persistence\NewsletterQueryContainer;
use SprykerFeature\Zed\Newsletter\Persistence\Propel\SpyNewsletterSubscriber;

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
     * @return NewsletterSubscriberInterface|null
     */
    public function loadSubscriberByEmail($email)
    {
        $subscriberEntity = $this->queryContainer->querySubscriber()
            ->filterByEmail($email)
            ->findOne()
        ;

        if (null === $subscriberEntity) {
            return null;
        }

        return $this->convertSubscriberEntityToTransfer($subscriberEntity);
    }

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriberTransfer
     *
     * @return NewsletterSubscriberInterface
     */
    public function createSubscriberFromTransfer(NewsletterSubscriberInterface $newsletterSubscriberTransfer)
    {
        $subscriberEntity = new SpyNewsletterSubscriber();
        $subscriberEntity->fromArray($newsletterSubscriberTransfer->toArray());

        if (null === $subscriberEntity->getSubscriberKey()) {
            $subscriberKey = $this->subscriberKeyGenerator->generateKey();
            $subscriberEntity->setSubscriberKey($subscriberKey);
        }

        $subscriberEntity->save();

        return $this->convertSubscriberEntityToTransfer($subscriberEntity);
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
