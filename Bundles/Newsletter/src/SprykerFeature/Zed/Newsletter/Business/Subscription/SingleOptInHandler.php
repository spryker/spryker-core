<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use SprykerFeature\Zed\Newsletter\Business\Exception\MissingNewsletterSubscriberException;

class SingleOptInHandler extends AbstractOptInHandler implements SubscriberOptInHandlerInterface
{

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
            $subscriberEntity->setIsConfirmed(true);
            $subscriberEntity->save();
        }
    }

}
