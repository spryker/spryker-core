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
     * @param string $currentStatus
     * @param string $newStatus
     *
     * @return bool
     */
    public function isMerchantStatusTransitionValid(string $currentStatus, string $newStatus): bool
    {
        if ($currentStatus === $newStatus) {
            return true;
        }

        if (!$this->isTransitionToStatusAllowed($currentStatus, $newStatus)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $currentStatus
     * @param string $newStatus
     *
     * @return bool
     */
    protected function isTransitionToStatusAllowed(string $currentStatus, string $newStatus): bool
    {
        $applicableMerchantStatuses = $this->merchantStatusReader->getApplicableMerchantStatuses($currentStatus);

        return in_array($newStatus, $applicableMerchantStatuses);
    }
}
