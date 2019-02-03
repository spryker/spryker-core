<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;

class AvailabilitySubscriptionFinder implements AvailabilitySubscriptionFinderInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionReaderInterface
     */
    protected $availabilitySubscriptionReader;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionReaderInterface $availabilitySubscriptionReader
     */
    public function __construct(AvailabilitySubscriptionReaderInterface $availabilitySubscriptionReader)
    {
        $this->availabilitySubscriptionReader = $availabilitySubscriptionReader;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function findAvailabilitySubscription(
        AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionRequestTransfer
    ): AvailabilitySubscriptionResponseTransfer {
        if ($availabilitySubscriptionRequestTransfer->getSubscriptionKey() !== null) {
            return $this->findBySubscriptionKey($availabilitySubscriptionRequestTransfer);
        }

        return $this->findByCustomerReferenceAndSku($availabilitySubscriptionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    protected function findBySubscriptionKey(
        AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionRequestTransfer
    ): AvailabilitySubscriptionResponseTransfer {
        $availabilitySubscriptionRequestTransfer->requireSubscriptionKey();

        $availabilitySubscription = $this->availabilitySubscriptionReader
            ->findOneBySubscriptionKey($availabilitySubscriptionRequestTransfer->getSubscriptionKey());

        return (new AvailabilitySubscriptionResponseTransfer())
            ->setAvailabilitySubscription($availabilitySubscription)
            ->setIsSuccess($availabilitySubscription !== null);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    protected function findByCustomerReferenceAndSku(
        AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionRequestTransfer
    ): AvailabilitySubscriptionResponseTransfer {
        $availabilitySubscriptionRequestTransfer->requireCustomerReference();
        $availabilitySubscriptionRequestTransfer->requireSku();

        $availabilitySubscriptionTransfer = $this->availabilitySubscriptionReader
            ->findOneByCustomerReferenceAndSku(
                $availabilitySubscriptionRequestTransfer->getCustomerReference(),
                $availabilitySubscriptionRequestTransfer->getSku()
            );

        return (new AvailabilitySubscriptionResponseTransfer())
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer)
            ->setIsSuccess($availabilitySubscriptionTransfer !== null);
    }
}
