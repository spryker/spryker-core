<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
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
     * @throws MissingNewsletterSubscriberException
     */
    public function approveSubscriberByKey(NewsletterSubscriberInterface $newsletterSubscriber)
    {
        $subscriberEntity = $this->queryContainer->querySubscriber()
            ->findOneBySubscriberKey($newsletterSubscriber->getSubscriberKey())
        ;

        if (null === $subscriberEntity) {
            throw new MissingNewsletterSubscriberException(sprintf(
                'Newsletter subscriber could not be found by subscriber key "%s".',
                $newsletterSubscriber->getSubscriberKey()
            ));
        }

        $subscriberEntity->setIsConfirmed(true);
        $subscriberEntity->save();
    }

}
