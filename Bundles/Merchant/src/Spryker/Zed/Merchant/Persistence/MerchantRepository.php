<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Merchant\Persistence\MerchantPersistenceFactory getFactory()
 */
class MerchantRepository extends AbstractRepository implements MerchantRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getMerchantById(int $idMerchant): MerchantTransfer
    {
        $spyMerchant = $this->getFactory()
            ->createMerchantQuery()
            ->filterByIdMerchant($idMerchant)
            ->findOne();

        return $this->getFactory()
            ->createMerchantMapper()
            ->mapEntityToMerchantTransfer($spyMerchant, new MerchantTransfer());
    }
}
