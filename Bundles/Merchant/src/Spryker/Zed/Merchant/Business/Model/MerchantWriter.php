<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface;

class MerchantWriter implements MerchantWriterInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $entityManager
     */
    public function __construct(MerchantEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function create(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantTransfer->requireName();

        return $this->entityManager->saveMerchant($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function update(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantTransfer
            ->requireIdMerchant()
            ->requireName();

        return $this->entityManager->saveMerchant($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function delete(MerchantTransfer $merchantTransfer): void
    {
        $merchantTransfer->requireIdMerchant();

        $this->entityManager->deleteMerchantById($merchantTransfer->getIdMerchant());
    }
}
