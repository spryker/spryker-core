<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller;

use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\MerchantSalesReturnMerchantUserGuiCommunicationFactory getFactory()
 */
class DetailController extends AbstractController
{
    protected const PARAM_ID_RETURN = 'id-return';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\IndexController::indexAction()
     */
    protected const ROUTE_RETURN_LIST = '/merchant-sales-return-merchant-user-gui';

    protected const MESSAGE_RETURN_NOT_FOUND = 'Requested return with ID "%id%" was not found.';
    protected const MESSAGE_PARAM_ID = '%id%';
    protected const DEFAULT_LABEL_CLASS = 'label-default';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idSalesReturn = $this->castId($request->get(static::PARAM_ID_RETURN));
        $returnTransfer = $this->findReturn($request);

        if (!$returnTransfer) {
            $this->addErrorMessage(static::MESSAGE_RETURN_NOT_FOUND, [
                static::MESSAGE_PARAM_ID => $idSalesReturn,
            ]);

            return $this->redirectResponse(
                Url::generate(static::ROUTE_RETURN_LIST)->build()
            );
        }

        $salesOrderItemIds = $this->extractSalesOrderItemIdsFromReturn($returnTransfer);
        $merchantOrderItemTransfers = $this
            ->getFactory()
            ->createMerchantOrderItemReader()
            ->findMerchantOrderItems($salesOrderItemIds);

        return [
            'return' => $returnTransfer,
            'customer' => $this->getFactory()->createCustomerReader()->getCustomerFromReturn($returnTransfer),
            'uniqueOrderReferences' => $this->extractUniqueOrderReferencesFromReturn($returnTransfer),
            'uniqueItemStateLabels' => $this->extractUniqueItemStateLabelsFromReturn($returnTransfer),
            'uniqueOrderItemManualEvents' => $this->extractUniqueOrderItemManualEvents($merchantOrderItemTransfers),
            'merchantOrderItems' => $merchantOrderItemTransfers,
            'salesOrderItemIds' => $salesOrderItemIds,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return int[]
     */
    protected function extractSalesOrderItemIdsFromReturn(ReturnTransfer $returnTransfer): array
    {
        $salesOrderItemIds = [];

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $salesOrderItemIds[] = $returnItemTransfer->getOrderItem()->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer|null
     */
    protected function findReturn(Request $request): ?ReturnTransfer
    {
        $idSalesReturn = $this->castId(
            $request->get(static::PARAM_ID_RETURN)
        );

        return $this->getFactory()
            ->getSalesReturnFacade()
            ->getReturns((new ReturnFilterTransfer())->addIdReturn($idSalesReturn))
            ->getReturns()
            ->getIterator()
            ->current();
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return string[]
     */
    protected function extractUniqueOrderReferencesFromReturn(ReturnTransfer $returnTransfer): array
    {
        $uniqueOrderReferences = [];

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $idSalesOrder = $returnItemTransfer->getOrderItem()->getFkSalesOrder();
            $orderReference = $returnItemTransfer->getOrderItem()->getOrderReference();

            $uniqueOrderReferences[$idSalesOrder] = $orderReference;
        }

        return $uniqueOrderReferences;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return string[]
     */
    protected function extractUniqueItemStateLabelsFromReturn(ReturnTransfer $returnTransfer): array
    {
        $uniqueItemStates = [];

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $state = $returnItemTransfer->getOrderItem()->getState()->getName();

            $uniqueItemStates[$state] = $this
                    ->getFactory()
                    ->getConfig()
                    ->getItemStateToLabelClassMapping()[$state] ?? static::DEFAULT_LABEL_CLASS;
        }

        return $uniqueItemStates;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer[] $merchantOrderItemTransfers
     *
     * @return string[]
     */
    protected function extractUniqueOrderItemManualEvents(array $merchantOrderItemTransfers): array
    {
        $allOrderItemManualEvents = [];

        foreach ($merchantOrderItemTransfers as $merchantOrderItem) {
            $allOrderItemManualEvents = array_merge($allOrderItemManualEvents, $merchantOrderItem->getManualEvents());
        }

        return array_unique($allOrderItemManualEvents);
    }
}
