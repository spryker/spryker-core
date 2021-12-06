<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Creator;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DiscountStoreCreator implements DiscountStoreCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface
     */
    protected $discountEntityManager;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface $discountEntityManager
     */
    public function __construct(DiscountEntityManagerInterface $discountEntityManager)
    {
        $this->discountEntityManager = $discountEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return void
     */
    public function createDiscountStoreRelationships(DiscountConfiguratorTransfer $discountConfiguratorTransfer): void
    {
        if (!$discountConfiguratorTransfer->getDiscountGeneralOrFail()->getStoreRelation()) {
            return;
        }

        $this->getTransactionHandler()->handleTransaction(function () use ($discountConfiguratorTransfer) {
            $this->executeCreateDiscountStoreRelationshipsTransaction($discountConfiguratorTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return void
     */
    protected function executeCreateDiscountStoreRelationshipsTransaction(DiscountConfiguratorTransfer $discountConfiguratorTransfer): void
    {
        $discountGeneralTransfer = $discountConfiguratorTransfer->getDiscountGeneralOrFail();

        $this->discountEntityManager->createDiscountStoreRelations(
            $discountGeneralTransfer->getIdDiscountOrFail(),
            $discountGeneralTransfer->getStoreRelationOrFail()->getIdStores(),
        );
    }
}
