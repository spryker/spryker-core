<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\MerchantRelationship\Business\Exception\MerchantRelationshipNotFoundException;

/**
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipPersistenceFactory getFactory()
 */
class MerchantRelationshipRepository extends AbstractRepository implements MerchantRelationshipRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idMerchantRelationship
     *
     * @throws \Spryker\Zed\MerchantRelationship\Business\Exception\MerchantRelationshipNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function getMerchantRelationshipById(int $idMerchantRelationship): MerchantRelationshipTransfer
    {
        $spyMerchantRelation = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->filterByIdMerchantRelationship($idMerchantRelationship)
            ->findOne();

        if (!$spyMerchantRelation) {
            throw new MerchantRelationshipNotFoundException();
        }

        return $this->getFactory()
            ->createMerchantRelationshipMapper()
            ->mapEntityToMerchantRelationshipTransfer($spyMerchantRelation, new MerchantRelationshipTransfer());
    }
}
