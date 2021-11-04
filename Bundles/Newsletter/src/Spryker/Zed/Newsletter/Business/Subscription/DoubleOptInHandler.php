<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer;
use Spryker\Shared\Newsletter\Messages\Messages;
use Spryker\Zed\Newsletter\Business\Exception\MissingNewsletterSubscriberException;

class DoubleOptInHandler extends AbstractOptInHandler implements SubscriberOptInHandlerInterface, DoubleOptInHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $subscriberTransfer
     *
     * @throws \Spryker\Zed\Newsletter\Business\Exception\MissingNewsletterSubscriberException
     *
     * @return void
     */
    public function optIn(NewsletterSubscriberTransfer $subscriberTransfer)
    {
        $subscriberEntity = $this->findSubscriberEntity($subscriberTransfer);

        if ($subscriberEntity === null) {
            throw new MissingNewsletterSubscriberException(sprintf(
                'Newsletter subscriber #%d could not be found.',
                $subscriberTransfer->getIdNewsletterSubscriber(),
            ));
        }

        if ($subscriberEntity->getIsConfirmed() === false) {
            $subscriberTransfer->fromArray($subscriberEntity->toArray(), true);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer
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
