<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\MerchantSalesReturnMerchantUserGuiCommunicationFactory getFactory()
 */
class DetailController extends AbstractController
{
    protected const PARAM_ID_RETURN = 'id-return';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\IndexController::indexAction()
     */
    protected const ROUTE_RETURN_LIST = '/merchant-sales-return-merchant-user-gui';

    protected const MESSAGE_RETURN_NOT_FOUND_ERROR = 'Requested return with ID %id% was not found.';
    protected const MESSAGE_PARAM_ID = '%id%';
    protected const DEFAULT_LABEL_CLASS = 'label-default';
    protected const MESSAGE_MERCHANT_NOT_FOUND_ERROR = 'Merchant for current user not found.';
    protected const MESSAGE_MERCHANT_ORDER_NOT_FOUND_ERROR = 'Merchant sales order #%d not found.';

    /**
     * @phpstan-return array<mixed>|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchant();

        if (!$idMerchant) {
            return $this->redirectToReturnList(static::MESSAGE_MERCHANT_NOT_FOUND_ERROR);
        }

        $idSalesReturn = $this->castId($request->get(static::PARAM_ID_RETURN));
        $returnTransfer = $this->findReturn($request);

        if (!$returnTransfer) {
            return $this->redirectToReturnList(static::MESSAGE_RETURN_NOT_FOUND_ERROR, [
                static::MESSAGE_PARAM_ID => $idSalesReturn,
            ]);
        }

        $merchantOrderTransfer = $this->findMerchantOrder($returnTransfer);

        if (!$merchantOrderTransfer) {
            return $this->redirectToReturnList(static::MESSAGE_MERCHANT_ORDER_NOT_FOUND_ERROR, [
                '%d' => $returnTransfer->getMerchantSalesOrderReference(),
            ]);
        }

        $salesOrderItemIds = $this->extractSalesOrderItemIdsFromReturn($returnTransfer);
        $merchantOrderItemTransfers = $this->getMerchantOrderItems($salesOrderItemIds);
        $merchantOrderTransfer->setMerchantOrderItems(new ArrayObject($merchantOrderItemTransfers));

        return [
            'return' => $returnTransfer,
            'customer' => $this->getFactory()->createCustomerReader()->findCustomerByReturn($returnTransfer),
            'uniqueOrderReferences' => $this->extractUniqueOrderReferencesFromReturn($returnTransfer),
            'uniqueItemStateLabels' => $this->extractUniqueItemStateLabelsFromReturn($merchantOrderItemTransfers),
            'uniqueOrderItemManualEvents' => $this->extractUniqueOrderItemManualEvents($merchantOrderItemTransfers),
            'merchantOrder' => $merchantOrderTransfer,
        ];
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
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    protected function findMerchantOrder(ReturnTransfer $returnTransfer): ?MerchantOrderTransfer
    {
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setMerchantOrderReference($returnTransfer->getMerchantSalesOrderReference())
            ->setWithItems(true);

        return $this
            ->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder($merchantOrderCriteriaTransfer);
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer[]
     */
    protected function getMerchantOrderItems(array $salesOrderItemIds): array
    {
        $merchantOrderItemCriteriaTransfer = (new MerchantOrderItemCriteriaTransfer())
            ->setOrderItemIds($salesOrderItemIds);

        return $this
            ->getFactory()
            ->createMerchantOrderReader()
            ->getMerchantOrderItems($merchantOrderItemCriteriaTransfer);
    }

    /**
     * @phpstan-return array<int, string>
     *
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return string[]
     */
    protected function extractUniqueOrderReferencesFromReturn(ReturnTransfer $returnTransfer): array
    {
        $uniqueOrderReferences = [];

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $orderItemTransfer = $returnItemTransfer->getOrderItemOrFail();
            $idSalesOrder = $orderItemTransfer->getFkSalesOrderOrFail();
            $orderReference = $orderItemTransfer->getOrderReferenceOrFail();

            $uniqueOrderReferences[$idSalesOrder] = $orderReference;
        }

        return $uniqueOrderReferences;
    }

    /**
     * @phpstan-return array<string, string>
     *
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer[] $merchantOrderItemTransfers
     *
     * @return string[]
     */
    protected function extractUniqueItemStateLabelsFromReturn(array $merchantOrderItemTransfers): array
    {
        $uniqueItemStates = [];

        foreach ($merchantOrderItemTransfers as $merchantOrderItemTransfer) {
            $state = $merchantOrderItemTransfer->getState();

            $uniqueItemStates[$state] = $this
                    ->getFactory()
                    ->getConfig()
                    ->getItemStateToLabelClassMapping()[$state] ?? static::DEFAULT_LABEL_CLASS;
        }

        return $uniqueItemStates;
    }

    /**
     * @phpstan-return array<int, string>
     *
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

    /**
     * @phpstan-return array<int, int>
     *
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return int[]
     */
    protected function extractSalesOrderItemIdsFromReturn(ReturnTransfer $returnTransfer): array
    {
        $salesOrderItemIds = [];

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $salesOrderItemIds[] = $returnItemTransfer->getOrderItemOrFail()->getIdSalesOrderItemOrFail();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param string $message
     * @param mixed[] $data
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToReturnList(string $message, array $data = []): RedirectResponse
    {
        $this->addErrorMessage($message, $data);
        $redirectUrl = Url::generate(static::ROUTE_RETURN_LIST)->build();

        return $this->redirectResponse($redirectUrl);
    }
}
