<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Merchant\Business\Exception\MerchantNotFoundException;

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
     * @throws \Spryker\Zed\Merchant\Business\Exception\MerchantNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getMerchantById(int $idMerchant): MerchantTransfer
    {
        $spyMerchant = $this->getFactory()
            ->createMerchantQuery()
            ->filterByIdMerchant($idMerchant)
            ->findOne();

        if (!$spyMerchant) {
            throw new MerchantNotFoundException();
        }

        return $this->getFactory()
            ->createMerchantMapper()
            ->mapEntityToMerchantTransfer($spyMerchant, new MerchantTransfer());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchants(): MerchantCollectionTransfer
    {
        $spyMerchants = $this->getFactory()
            ->createMerchantQuery()
            ->orderByName()
            ->find();

        $merchantsCollection = new MerchantCollectionTransfer();
        foreach ($spyMerchants as $spyMerchant) {
            $merchantsCollection->addMerchants(
                $this->getFactory()
                ->createMerchantMapper()
                ->mapEntityToMerchantTransfer($spyMerchant, new MerchantTransfer())
            );
        }

        return $merchantsCollection;
    }
}
