<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionAmountExpanderInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionAmountReaderInterface;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface;

class MerchantCommissionAmountUpdater implements MerchantCommissionAmountUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionAmountExpanderInterface
     */
    protected MerchantCommissionAmountExpanderInterface $merchantCommissionAmountExpander;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionAmountReaderInterface
     */
    protected MerchantCommissionAmountReaderInterface $merchantCommissionAmountReader;

    /**
     * @var \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface
     */
    protected MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionAmountExpanderInterface $merchantCommissionAmountExpander
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionAmountReaderInterface $merchantCommissionAmountReader
     * @param \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager
     */
    public function __construct(
        MerchantCommissionAmountExpanderInterface $merchantCommissionAmountExpander,
        MerchantCommissionAmountReaderInterface $merchantCommissionAmountReader,
        MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager
    ) {
        $this->merchantCommissionAmountExpander = $merchantCommissionAmountExpander;
        $this->merchantCommissionAmountReader = $merchantCommissionAmountReader;
        $this->merchantCommissionEntityManager = $merchantCommissionEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function updateMerchantCommissionAmounts(
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer {
        $persistedMerchantCommissionAmountCollectionTransfer = $this->merchantCommissionAmountReader
            ->getMerchantCommissionAmountCollectionByMerchantCommissionIds([$merchantCommissionTransfer->getIdMerchantCommissionOrFail()]);
        $persistedMerchantCommissionAmountTransfersIndexedByCurrencyCode = $this->getMerchantCommissionAmountTransfersIndexedByCurrencyCode(
            $persistedMerchantCommissionAmountCollectionTransfer->getMerchantCommissionAmounts(),
        );
        $merchantCommissionAmountTransfers = $this->merchantCommissionAmountExpander->expandMerchantCommissionAmountsWithCurrency(
            $merchantCommissionTransfer->getMerchantCommissionAmounts(),
        );

        $this->getTransactionHandler()->handleTransaction(function () use ($merchantCommissionTransfer, $merchantCommissionAmountTransfers, $persistedMerchantCommissionAmountTransfersIndexedByCurrencyCode): void {
            $this->executeUpdateMerchantCommissionAmountsTransaction($merchantCommissionTransfer, $merchantCommissionAmountTransfers, $persistedMerchantCommissionAmountTransfersIndexedByCurrencyCode);
        });

        return $merchantCommissionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer> $merchantCommissionAmountTransfers
     * @param array<string, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer> $persistedMerchantCommissionAmountTransfersIndexedByCurrencyCode
     *
     * @return void
     */
    protected function executeUpdateMerchantCommissionAmountsTransaction(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        ArrayObject $merchantCommissionAmountTransfers,
        array $persistedMerchantCommissionAmountTransfersIndexedByCurrencyCode
    ): void {
        $idMerchantCommission = $merchantCommissionTransfer->getIdMerchantCommissionOrFail();
        foreach ($merchantCommissionAmountTransfers as $merchantCommissionAmountTransfer) {
            $merchantCommissionAmountTransfer->setFkMerchantCommission($idMerchantCommission);

            $currencyCode = $merchantCommissionAmountTransfer->getCurrencyOrFail()->getCodeOrFail();
            if (isset($persistedMerchantCommissionAmountTransfersIndexedByCurrencyCode[$currencyCode])) {
                $this->merchantCommissionEntityManager->updateMerchantCommissionAmount($merchantCommissionAmountTransfer);

                continue;
            }

            if ($merchantCommissionAmountTransfer->getUuid() === null) {
                $this->merchantCommissionEntityManager->createMerchantCommissionAmount($merchantCommissionAmountTransfer);

                continue;
            }

            $this->merchantCommissionEntityManager->deleteMerchantCommissionAmount($merchantCommissionAmountTransfer);
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer> $merchantCommissionAmountTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer>
     */
    protected function getMerchantCommissionAmountTransfersIndexedByCurrencyCode(ArrayObject $merchantCommissionAmountTransfers): array
    {
        $indexedMerchantCommissionAmountTransfers = [];
        foreach ($merchantCommissionAmountTransfers as $merchantCommissionAmountTransfer) {
            $currencyCode = $merchantCommissionAmountTransfer->getCurrencyOrFail()->getCodeOrFail();
            $indexedMerchantCommissionAmountTransfers[$currencyCode] = $merchantCommissionAmountTransfer;
        }

        return $indexedMerchantCommissionAmountTransfers;
    }
}
