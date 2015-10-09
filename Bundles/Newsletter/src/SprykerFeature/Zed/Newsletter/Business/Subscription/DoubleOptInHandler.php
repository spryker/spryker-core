<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Exception;
use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use SprykerFeature\Zed\Newsletter\Business\Exception\MissingNewsletterSubscriberException;

class DoubleOptInHandler extends AbstractOptInHandler implements SubscriberOptInHandlerInterface
{

    /**
     * @var SubscriberOptInSenderInterface[]
     */
    protected $subscriberOptInSenders;

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
     * @throws Exception
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

        if (false === $subscriberEntity->getIsConfirmed() && null === $subscriberEntity->getSubscriberKey()) {
            $connection = $this->queryContainer->getConnection();
            $connection->beginTransaction();

            try {
                $this->setSubscriberKey($subscriberEntity);

                $subscriberTransfer->fromArray($subscriberEntity->toArray(), true);

                $this->triggerSubscriberOptInSenders($subscriberTransfer);

                $connection->commit();
            } catch (Exception $e) {
                $connection->rollBack();
                throw $e;
            }
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

}
