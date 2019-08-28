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
    protected $merchantRepository;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $merchantRepository
     */
    public function __construct(MerchantRepositoryInterface $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchantByIdMerchant(int $idMerchant): ?MerchantTransfer
    {
        return $this->merchantRepository->findMerchantByIdMerchant($idMerchant);
    }

    /**
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchantByEmail(string $email): ?MerchantTransfer
    {
        return $this->merchantRepository->findMerchantByEmail($email);
    }
}
