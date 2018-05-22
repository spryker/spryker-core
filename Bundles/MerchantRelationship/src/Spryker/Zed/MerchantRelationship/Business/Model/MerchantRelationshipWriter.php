<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Model;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface;

class MerchantRelationshipWriter implements MerchantRelationshipWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface $entityManager
     */
    public function __construct(MerchantRelationshipEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function create(MerchantRelationshipTransfer $merchantRelationTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationTransfer->requireFkMerchant()
            ->requireFkCompanyBusinessUnit();

        return $this->entityManager->saveMerchantRelationship($merchantRelationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function update(MerchantRelationshipTransfer $merchantRelationTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationTransfer->requireIdMerchantRelationship()
            ->requireFkMerchant()
            ->requireFkCompanyBusinessUnit();

        return $this->entityManager->saveMerchantRelationship($merchantRelationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationTransfer
     *
     * @return void
     */
    public function delete(MerchantRelationshipTransfer $merchantRelationTransfer): void
    {
        $merchantRelationTransfer->requireIdMerchantRelationship();

        $this->entityManager->deleteMerchantRelationshipById($merchantRelationTransfer->getIdMerchantRelationship());
    }
}
