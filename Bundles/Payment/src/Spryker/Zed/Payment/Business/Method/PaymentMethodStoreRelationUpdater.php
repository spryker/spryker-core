<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Method;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface;

class PaymentMethodStoreRelationUpdater implements PaymentMethodStoreRelationUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface
     */
    protected $paymentEntityManager;

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface
     */
    protected $paymentRepository;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface $paymentEntityManager
     * @param \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface $paymentRepository
     */
    public function __construct(
        PaymentEntityManagerInterface $paymentEntityManager,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->paymentEntityManager = $paymentEntityManager;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    public function update(StoreRelationTransfer $storeRelationTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($storeRelationTransfer) {
            $this->executeUpdateStoreRelationTransaction($storeRelationTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    protected function executeUpdateStoreRelationTransaction(StoreRelationTransfer $storeRelationTransfer): void
    {
        $storeRelationTransfer->requireIdEntity();

        $currentIdStores = $this->getIdStoresByIdShipmentMethod($storeRelationTransfer->getIdEntity());
        $requestedIdStores = $storeRelationTransfer->getIdStores() ?? [];

        $saveIdStores = array_diff($requestedIdStores, $currentIdStores);
        $deleteIdStores = array_diff($currentIdStores, $requestedIdStores);

        $this->paymentEntityManager->addPaymentMethodStoreRelationsForStores($saveIdStores, $storeRelationTransfer->getIdEntity());
        $this->paymentEntityManager->removePaymentMethodStoreRelationsForStores($deleteIdStores, $storeRelationTransfer->getIdEntity());
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return int[]
     */
    protected function getIdStoresByIdShipmentMethod(int $idShipmentMethod): array
    {
        $storeRelation = $this->paymentRepository->getStoreRelationByIdPaymentMethod($idShipmentMethod);

        return $storeRelation->getIdStores();
    }
}
