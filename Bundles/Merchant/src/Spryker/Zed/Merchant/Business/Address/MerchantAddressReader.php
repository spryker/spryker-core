<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Address;

use Generated\Shared\Transfer\MerchantAddressTransfer;
use Spryker\Zed\Merchant\Business\Exception\MerchantAddressNotFoundException;
use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;

class MerchantAddressReader implements MerchantAddressReaderInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $repository
     */
    public function __construct(MerchantRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @throws \Spryker\Zed\Merchant\Business\Exception\MerchantAddressNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function getMerchantAddressById(MerchantAddressTransfer $merchantAddressTransfer): MerchantAddressTransfer
    {
        $merchantAddressTransfer->requireIdMerchantAddress();

        $merchantAddressTransfer = $this->repository->getMerchantAddressById($merchantAddressTransfer->getIdMerchantAddress());
        if (!$merchantAddressTransfer) {
            throw new MerchantAddressNotFoundException();
        }

        return $merchantAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer|null
     */
    public function findMerchantAddressById(MerchantAddressTransfer $merchantAddressTransfer): ?MerchantAddressTransfer
    {
        $merchantAddressTransfer->requireIdMerchantAddress();

        return $this->repository->getMerchantAddressById($merchantAddressTransfer->getIdMerchantAddress());
    }
}
