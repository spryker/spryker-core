<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model\Status;

class MerchantStatusValidator implements MerchantStatusValidatorInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Business\Model\Status\MerchantStatusReaderInterface
     */
    protected $merchantStatusReader;

    /**
     * @param \Spryker\Zed\Merchant\Business\Model\Status\MerchantStatusReaderInterface $merchantStatusReader
     */
    public function __construct(
        MerchantStatusReaderInterface $merchantStatusReader
    ) {
        $this->merchantStatusReader = $merchantStatusReader;
    }

    /**
     * @param string $newStatus
     * @param string $currentStatus
     *
     * @return bool
     */
    public function isMerchantStatusTransitionValid(string $newStatus, string $currentStatus): bool
    {
        if ($newStatus === $currentStatus) {
            return true;
        }

        if (!$this->isTransitionToStatusAllowed($newStatus, $currentStatus)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $newStatus
     * @param string $currentStatus
     *
     * @return bool
     */
    protected function isTransitionToStatusAllowed(string $newStatus, string $currentStatus): bool
    {
        $applicableMerchantStatuses = $this->merchantStatusReader->getApplicableMerchantStatuses($currentStatus);

        return in_array($newStatus, $applicableMerchantStatuses);
    }
}
