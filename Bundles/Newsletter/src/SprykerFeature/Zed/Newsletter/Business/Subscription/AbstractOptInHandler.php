<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainer;
use Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriber;

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
     * @param NewsletterSubscriberTransfer $subscriberTransfer
     *
     * @return SpyNewsletterSubscriber
     */
    protected function findSubscriberEntity(NewsletterSubscriberTransfer $subscriberTransfer)
    {
        $subscriberQuery = $this->queryContainer->querySubscriber();

        $idNewsletterSubscriber = $subscriberTransfer->getIdNewsletterSubscriber();
        if ($idNewsletterSubscriber !== null) {
            return $subscriberQuery->findOneByIdNewsletterSubscriber($idNewsletterSubscriber);
        }

        $email = $subscriberTransfer->getEmail();
        if ($email !== null) {
            return $subscriberQuery->findOneByEmail($email);
        }

        return null;
    }

}
