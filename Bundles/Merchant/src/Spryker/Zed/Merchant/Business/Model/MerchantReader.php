<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;

class MerchantReader implements MerchantReaderInterface
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
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getMerchantById(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantTransfer->requireIdMerchant();

        return $this->repository->getMerchantById($merchantTransfer->getIdMerchant());
    }
}
