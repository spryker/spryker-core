<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceResponseTransfer;

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
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceResponseTransfer
     */
    public function checkExistence(
        AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
    ): AvailabilitySubscriptionExistenceResponseTransfer {
        if ($availabilitySubscriptionExistenceRequestTransfer->getSubscriptionKey() !== null) {
            return $this->findBySubscriptionKey($availabilitySubscriptionExistenceRequestTransfer);
        }

        return $this->findByEmailAndSku($availabilitySubscriptionExistenceRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceResponseTransfer
     */
    protected function findBySubscriptionKey(
        AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
    ): AvailabilitySubscriptionExistenceResponseTransfer {
        $availabilitySubscriptionExistenceRequestTransfer->requireSubscriptionKey();

        $availabilitySubscription = $this->availabilitySubscriptionReader
            ->findOneBySubscriptionKey($availabilitySubscriptionExistenceRequestTransfer->getSubscriptionKey());

        return (new AvailabilitySubscriptionExistenceResponseTransfer())
            ->setAvailabilitySubscription($availabilitySubscription);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceResponseTransfer
     */
    protected function findByEmailAndSku(
        AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
    ): AvailabilitySubscriptionExistenceResponseTransfer {
        $availabilitySubscriptionExistenceRequestTransfer->requireEmail();
        $availabilitySubscriptionExistenceRequestTransfer->requireSku();

        $availabilitySubscriptionTransfer = $this->availabilitySubscriptionReader
            ->findOneByEmailAndSku(
                $availabilitySubscriptionExistenceRequestTransfer->getEmail(),
                $availabilitySubscriptionExistenceRequestTransfer->getSku()
            );

        return (new AvailabilitySubscriptionExistenceResponseTransfer())
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer);
    }
}
