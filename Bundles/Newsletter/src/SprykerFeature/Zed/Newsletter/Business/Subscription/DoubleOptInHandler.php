<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionApprovalResultInterface;
use Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer;
use SprykerFeature\Shared\Newsletter\Messages\Messages;
use SprykerFeature\Zed\Newsletter\Business\Exception\MissingNewsletterSubscriberException;

class DoubleOptInHandler extends AbstractOptInHandler implements SubscriberOptInHandlerInterface, DoubleOptInHandlerInterface
{

    /**
     * @var SubscriberOptInSenderInterface[]
     */
    protected $subscriberOptInSenders = [];

    /**
     * @param SubscriberOptInSenderInterface $subscriberOptInSender
     */
    public function addSubscriberOptInSender(SubscriberOptInSenderInterface $subscriberOptInSender)
    {
        $this->subscriberOptInSenders[] = $subscriberOptInSender;
    }

    /**
     * @param NewsletterSubscriberInterface $subscriberTransfer
     *
     * @throws MissingNewsletterSubscriberException
     */
    public function optIn(NewsletterSubscriberInterface $subscriberTransfer)
    {
        $subscriberEntity = $this->findSubscriberEntity($subscriberTransfer);

        if (null === $subscriberEntity) {
            throw new MissingNewsletterSubscriberException(sprintf(
                'Newsletter subscriber #%d could not be found.',
                $subscriberTransfer->getIdNewsletterSubscriber()
            ));
        }

        if (false === $subscriberEntity->getIsConfirmed()) {
            $subscriberTransfer->fromArray($subscriberEntity->toArray(), true);

            $this->triggerSubscriberOptInSenders($subscriberTransfer);
        }
    }

    /**
     * @param NewsletterSubscriberInterface $subscriberTransfer
     */
    protected function triggerSubscriberOptInSenders(NewsletterSubscriberInterface $subscriberTransfer)
    {
        foreach ($this->subscriberOptInSenders as $sender) {
            $sender->send($subscriberTransfer);
        }
    }

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     *
     * @return NewsletterSubscriptionApprovalResultInterface
     */
    public function approveSubscriberByKey(NewsletterSubscriberInterface $newsletterSubscriber)
    {
        $result = new NewsletterSubscriptionApprovalResultTransfer();

        $subscriberEntity = $this->queryContainer->querySubscriber()
            ->findOneBySubscriberKey($newsletterSubscriber->getSubscriberKey())
        ;

        if (null === $subscriberEntity) {
            $result->setIsSuccess(false);
            $result->setErrorMessage(Messages::INVALID_SUBSCRIBER_KEY);

            return $result;
        }

        $subscriberEntity->setIsConfirmed(true);
        $subscriberEntity->save();
        $result->setIsSuccess(true);

        return $result;
    }

}
