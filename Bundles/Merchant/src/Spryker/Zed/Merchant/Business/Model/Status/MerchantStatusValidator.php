<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model\Status;

use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;

class MerchantStatusValidator implements MerchantStatusValidatorInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface
     */
    protected $merchantRepository;

    /**
     * @var \Spryker\Zed\Merchant\Business\Model\Status\MerchantStatusReaderInterface
     */
    protected $merchantStatusReader;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $merchantRepository
     * @param \Spryker\Zed\Merchant\Business\Model\Status\MerchantStatusReaderInterface $merchantStatusReader
     */
    public function __construct(
        MerchantRepositoryInterface $merchantRepository,
        MerchantStatusReaderInterface $merchantStatusReader
    ) {
        $this->merchantRepository = $merchantRepository;
        $this->merchantStatusReader = $merchantStatusReader;
    }

    /**
     * @param int $idMerchant
     * @param string $newStatus
     *
     * @return bool
     */
    public function isMerchantStatusTransitionValid(int $idMerchant, string $newStatus): bool
    {
        $existingMerchantTransfer = $this->merchantRepository->findMerchantByIdMerchant($idMerchant);

        if ($existingMerchantTransfer === null) {
            return false;
        }

        if ($newStatus === $existingMerchantTransfer->getStatus()) {
            return true;
        }

        if (!$this->isTransitionToStatusAllowed($newStatus, $existingMerchantTransfer->getStatus())) {
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
        $nextStatuses = $this->merchantStatusReader->getNextStatuses($currentStatus);

        return in_array($newStatus, $nextStatuses);
    }
}
