<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer;
use Spryker\Shared\Newsletter\Messages\Messages;
use Spryker\Zed\Newsletter\Business\Exception\MissingNewsletterSubscriberException;

class DoubleOptInHandler extends AbstractOptInHandler implements SubscriberOptInHandlerInterface, DoubleOptInHandlerInterface
{

    /**
     * @var SubscriberOptInSenderInterface[]
     */
    protected $subscriberOptInSenders = [];

    /**
     * @param SubscriberOptInSenderInterface $subscriberOptInSender
     *
     * @return DoubleOptInHandlerInterface
     */
    public function addSubscriberOptInSender(SubscriberOptInSenderInterface $subscriberOptInSender)
    {
        $this->subscriberOptInSenders[] = $subscriberOptInSender;

        return $this;
    }

    /**
     * @param NewsletterSubscriberTransfer $subscriberTransfer
     *
     * @throws MissingNewsletterSubscriberException
     *
     * @return void
     */
    public function optIn(NewsletterSubscriberTransfer $subscriberTransfer)
    {
        $subscriberEntity = $this->findSubscriberEntity($subscriberTransfer);

        if ($subscriberEntity === null) {
            throw new MissingNewsletterSubscriberException(sprintf(
                'Newsletter subscriber #%d could not be found.',
                $subscriberTransfer->getIdNewsletterSubscriber()
            ));
        }

        if ($subscriberEntity->getIsConfirmed() === false) {
            $subscriberTransfer->fromArray($subscriberEntity->toArray(), true);

            $this->triggerSubscriberOptInSenders($subscriberTransfer);
        }
    }

    /**
     * @param NewsletterSubscriberTransfer $subscriberTransfer
     *
     * @return void
     */
    protected function triggerSubscriberOptInSenders(NewsletterSubscriberTransfer $subscriberTransfer)
    {
        foreach ($this->subscriberOptInSenders as $sender) {
            $sender->send($subscriberTransfer);
        }
    }

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return NewsletterSubscriptionApprovalResultTransfer
     */
    public function approveSubscriberByKey(NewsletterSubscriberTransfer $newsletterSubscriber)
    {
        $result = new NewsletterSubscriptionApprovalResultTransfer();

        $subscriberEntity = $this->queryContainer->querySubscriber()
            ->findOneBySubscriberKey($newsletterSubscriber->getSubscriberKey());

        if ($subscriberEntity === null) {
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
