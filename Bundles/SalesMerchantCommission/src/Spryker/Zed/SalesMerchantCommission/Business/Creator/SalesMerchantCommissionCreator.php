<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Creator;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesMerchantCommission\Business\Mapper\MerchantCommissionMapperInterface;
use Spryker\Zed\SalesMerchantCommission\Business\Updater\OrderUpdaterInterface;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToMerchantCommissionFacadeInterface;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeInterface;
use Spryker\Zed\SalesMerchantCommission\Persistence\SalesMerchantCommissionEntityManagerInterface;

class SalesMerchantCommissionCreator implements SalesMerchantCommissionCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Persistence\SalesMerchantCommissionEntityManagerInterface
     */
    protected SalesMerchantCommissionEntityManagerInterface $salesMerchantCommissionEntityManager;

    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToMerchantCommissionFacadeInterface
     */
    protected SalesMerchantCommissionToMerchantCommissionFacadeInterface $merchantCommissionFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeInterface
     */
    protected SalesMerchantCommissionToSalesFacadeInterface $salesFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Business\Updater\OrderUpdaterInterface
     */
    protected OrderUpdaterInterface $orderUpdater;

    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Business\Mapper\MerchantCommissionMapperInterface
     */
    protected MerchantCommissionMapperInterface $merchantCommissionMapper;

    /**
     * @param \Spryker\Zed\SalesMerchantCommission\Persistence\SalesMerchantCommissionEntityManagerInterface $salesMerchantCommissionEntityManager
     * @param \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToMerchantCommissionFacadeInterface $merchantCommissionFacade
     * @param \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesMerchantCommission\Business\Updater\OrderUpdaterInterface $orderUpdater
     * @param \Spryker\Zed\SalesMerchantCommission\Business\Mapper\MerchantCommissionMapperInterface $merchantCommissionMapper
     */
    public function __construct(
        SalesMerchantCommissionEntityManagerInterface $salesMerchantCommissionEntityManager,
        SalesMerchantCommissionToMerchantCommissionFacadeInterface $merchantCommissionFacade,
        SalesMerchantCommissionToSalesFacadeInterface $salesFacade,
        OrderUpdaterInterface $orderUpdater,
        MerchantCommissionMapperInterface $merchantCommissionMapper
    ) {
        $this->salesMerchantCommissionEntityManager = $salesMerchantCommissionEntityManager;
        $this->merchantCommissionFacade = $merchantCommissionFacade;
        $this->salesFacade = $salesFacade;
        $this->orderUpdater = $orderUpdater;
        $this->merchantCommissionMapper = $merchantCommissionMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function createSalesMerchantCommissions(OrderTransfer $orderTransfer): void
    {
        $persistedOrderTransfer = $this->salesFacade->findOrderByIdSalesOrder($orderTransfer->getIdSalesOrderOrFail());
        if (!$persistedOrderTransfer) {
            return;
        }

        $merchantCommissionCalculationRequestTransfer = $this->merchantCommissionMapper
            ->mapOrderTransferToMerchantCommissionCalculationRequestTransfer(
                $persistedOrderTransfer,
                new MerchantCommissionCalculationRequestTransfer(),
            );

        $merchantCommissionCalculationResponseTransfer = $this->merchantCommissionFacade
            ->calculateMerchantCommission($merchantCommissionCalculationRequestTransfer);

        $this->getTransactionHandler()->handleTransaction(function () use ($persistedOrderTransfer, $merchantCommissionCalculationResponseTransfer): void {
            $this->executeCreateSalesMerchantCommissionsTransaction(
                $persistedOrderTransfer,
                $merchantCommissionCalculationResponseTransfer,
            );
        });
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer $merchantCommissionCalculationResponseTransfer
     *
     * @return void
     */
    protected function executeCreateSalesMerchantCommissionsTransaction(
        OrderTransfer $orderTransfer,
        MerchantCommissionCalculationResponseTransfer $merchantCommissionCalculationResponseTransfer
    ): void {
        $this->persistSalesMerchantCommissions($merchantCommissionCalculationResponseTransfer);
        $this->orderUpdater->updateOrderItemsWithTotals(
            $orderTransfer,
            $merchantCommissionCalculationResponseTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer $merchantCommissionCalculationResponseTransfer
     *
     * @return list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>
     */
    protected function persistSalesMerchantCommissions(MerchantCommissionCalculationResponseTransfer $merchantCommissionCalculationResponseTransfer): array
    {
        $persistedSalesMerchantCommissionTransfers = [];

        foreach ($merchantCommissionCalculationResponseTransfer->getItems() as $merchantCommissionCalculationItemTransfer) {
            $salesMerchantCommissionTransfers = $this->merchantCommissionMapper
                ->mapMerchantCommissionCalculationItemTransferToSalesMerchantCommissionTransfers(
                    $merchantCommissionCalculationItemTransfer,
                );

            foreach ($salesMerchantCommissionTransfers as $salesMerchantCommissionTransfer) {
                $persistedSalesMerchantCommissionTransfers[] = $this->salesMerchantCommissionEntityManager
                    ->createSalesMerchantCommission($salesMerchantCommissionTransfer);
            }
        }

        return $persistedSalesMerchantCommissionTransfers;
    }
}
