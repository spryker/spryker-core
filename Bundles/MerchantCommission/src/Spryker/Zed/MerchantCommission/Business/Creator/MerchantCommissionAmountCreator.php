<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionAmountExpanderInterface;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface;

class MerchantCommissionAmountCreator implements MerchantCommissionAmountCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionAmountExpanderInterface
     */
    protected MerchantCommissionAmountExpanderInterface $merchantCommissionAmountExpander;

    /**
     * @var \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface
     */
    protected MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionAmountExpanderInterface $merchantCommissionAmountExpander
     * @param \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager
     */
    public function __construct(
        MerchantCommissionAmountExpanderInterface $merchantCommissionAmountExpander,
        MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager
    ) {
        $this->merchantCommissionAmountExpander = $merchantCommissionAmountExpander;
        $this->merchantCommissionEntityManager = $merchantCommissionEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function createMerchantCommissionAmounts(
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer {
        $merchantCommissionAmountTransfers = $this->merchantCommissionAmountExpander->expandMerchantCommissionAmountsWithCurrency(
            $merchantCommissionTransfer->getMerchantCommissionAmounts(),
        );

        $this->getTransactionHandler()->handleTransaction(function () use ($merchantCommissionTransfer, $merchantCommissionAmountTransfers): void {
            $this->executeCreateMerchantCommissionAmountsTransaction($merchantCommissionTransfer, $merchantCommissionAmountTransfers);
        });

        return $merchantCommissionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer> $merchantCommissionAmountTransfers
     *
     * @return void
     */
    protected function executeCreateMerchantCommissionAmountsTransaction(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        ArrayObject $merchantCommissionAmountTransfers
    ): void {
        $idMerchantCommission = $merchantCommissionTransfer->getIdMerchantCommissionOrFail();

        foreach ($merchantCommissionAmountTransfers as $merchantCommissionAmountTransfer) {
            $merchantCommissionAmountTransfer->setFkMerchantCommission($idMerchantCommission);
            $this->merchantCommissionEntityManager->createMerchantCommissionAmount($merchantCommissionAmountTransfer);
        }
    }
}
