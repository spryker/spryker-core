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
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function findAvailabilitySubscription(
        AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
    ): AvailabilitySubscriptionResponseTransfer {
        if ($availabilitySubscriptionExistenceRequestTransfer->getSubscriptionKey() !== null) {
            return $this->findBySubscriptionKey($availabilitySubscriptionExistenceRequestTransfer);
        }

        return $this->findByEmailAndSku($availabilitySubscriptionExistenceRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    protected function findBySubscriptionKey(
        AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
    ): AvailabilitySubscriptionResponseTransfer {
        $availabilitySubscriptionExistenceRequestTransfer->requireSubscriptionKey();

        $availabilitySubscription = $this->availabilitySubscriptionReader
            ->findOneBySubscriptionKey($availabilitySubscriptionExistenceRequestTransfer->getSubscriptionKey());

        return (new AvailabilitySubscriptionResponseTransfer())
            ->setAvailabilitySubscription($availabilitySubscription)
            ->setIsSuccess($availabilitySubscription !== null);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    protected function findByEmailAndSku(
        AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
    ): AvailabilitySubscriptionResponseTransfer {
        $availabilitySubscriptionExistenceRequestTransfer->requireEmail();
        $availabilitySubscriptionExistenceRequestTransfer->requireSku();

        $availabilitySubscriptionTransfer = $this->availabilitySubscriptionReader
            ->findOneByEmailAndSku(
                $availabilitySubscriptionExistenceRequestTransfer->getEmail(),
                $availabilitySubscriptionExistenceRequestTransfer->getSku()
            );

        return (new AvailabilitySubscriptionResponseTransfer())
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer)
            ->setIsSuccess($availabilitySubscriptionTransfer !== null);
    }
}
