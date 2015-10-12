<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use SprykerFeature\Zed\Newsletter\Business\Exception\MissingNewsletterSubscriberException;
use SprykerFeature\Zed\Newsletter\Persistence\NewsletterQueryContainer;
use SprykerFeature\Zed\Newsletter\Persistence\Propel\SpyNewsletterSubscriber;

class SingleOptInHandler extends AbstractOptInHandler implements SubscriberOptInHandlerInterface
{

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
            $subscriberEntity->setIsConfirmed(true);
            $subscriberEntity->save();
        }
    }
}
