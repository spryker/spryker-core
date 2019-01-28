<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\FindAvailabilitySubscriptionRequestTransfer;
use Generated\Shared\Transfer\FindAvailabilitySubscriptionResponseTransfer;

class AvailabilitySubscriptionChecker implements AvailabilitySubscriptionCheckerInterface
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
     * @param \Generated\Shared\Transfer\FindAvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FindAvailabilitySubscriptionResponseTransfer
     */
    public function findAvailabilitySubscription(
        FindAvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
    ): FindAvailabilitySubscriptionResponseTransfer {
        if ($availabilitySubscriptionExistenceRequestTransfer->getSubscriptionKey() !== null) {
            return $this->findBySubscriptionKey($availabilitySubscriptionExistenceRequestTransfer);
        }

        return $this->findByEmailAndSku($availabilitySubscriptionExistenceRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FindAvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FindAvailabilitySubscriptionResponseTransfer
     */
    protected function findBySubscriptionKey(
        FindAvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
    ): FindAvailabilitySubscriptionResponseTransfer {
        $availabilitySubscriptionExistenceRequestTransfer->requireSubscriptionKey();

        $availabilitySubscription = $this->availabilitySubscriptionReader
            ->findOneBySubscriptionKey($availabilitySubscriptionExistenceRequestTransfer->getSubscriptionKey());

        return (new FindAvailabilitySubscriptionResponseTransfer())
            ->setAvailabilitySubscription($availabilitySubscription);
    }

    /**
     * @param \Generated\Shared\Transfer\FindAvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FindAvailabilitySubscriptionResponseTransfer
     */
    protected function findByEmailAndSku(
        FindAvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
    ): FindAvailabilitySubscriptionResponseTransfer {
        $availabilitySubscriptionExistenceRequestTransfer->requireEmail();
        $availabilitySubscriptionExistenceRequestTransfer->requireSku();

        $availabilitySubscriptionTransfer = $this->availabilitySubscriptionReader
            ->findOneByEmailAndSku(
                $availabilitySubscriptionExistenceRequestTransfer->getEmail(),
                $availabilitySubscriptionExistenceRequestTransfer->getSku()
            );

        return (new FindAvailabilitySubscriptionResponseTransfer())
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer);
    }
}
