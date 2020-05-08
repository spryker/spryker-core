<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Controller;

use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 */
class ReturnController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\ReturnController::indexAction()
     */
    protected const ROUTE_RETURN_LIST = '/sales-return-gui/return';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\ReturnController::detailAction()
     */
    protected const ROUTE_RETURN_DETAIL = '/sales-return-gui/return/detail';

    protected const PARAM_ID_SALES_RETURN = 'id-sales-return';

    protected const ERROR_MESSAGE_RETURN_NOT_FOUND = 'Requested return with ID "%id%" was not found.';
    protected const ERROR_MESSAGE_PARAM_ID = '%id%';

    /**
     * @return array
     */
    public function indexAction(): array
    {
        $returnTable = $this->getFactory()->createReturnTable();

        return $this->viewResponse([
            'returnTable' => $returnTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        return $this->jsonResponse(
            $this->getFactory()->createReturnTable()->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function detailAction(Request $request)
    {
        $response = $this->executeDetailAction($request);

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return $this->viewResponse($response);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeDetailAction(Request $request)
    {
        $idSalesReturn = $this->castId(
            $request->get(static::PARAM_ID_SALES_RETURN)
        );

        $returnCollectionTransfer = $this->getFactory()->getSalesReturnFacade()->getReturns(
            (new ReturnFilterTransfer())->addSalesReturnIds($idSalesReturn)
        );

        if (!$returnCollectionTransfer->getReturns()->count()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_RETURN_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idSalesReturn,
            ]);

            return $this->redirectResponse(
                Url::generate(static::ROUTE_RETURN_LIST)->build()
            );
        }

        /** @var \Generated\Shared\Transfer\ReturnTransfer $returnTransfer */
        $returnTransfer = $returnCollectionTransfer->getReturns()->getIterator()->current();

        $customerResponseTransfer = $this->getFactory()
            ->getCustomerFacade()
            ->findCustomerByReference($returnTransfer->getCustomerReference());

        $returnExtractor = $this->getFactory()->createReturnExtractor();

        $salesOrderItemIds = $returnExtractor->extractSalesOrderItemIdsFromReturn($returnTransfer);
        $orderItemFilterTransfer = (new OrderItemFilterTransfer())->setSalesOrderItemIds($salesOrderItemIds);

        $triggerButtonRedirectUrl = Url::generate(static::ROUTE_RETURN_DETAIL, [
            static::PARAM_ID_SALES_RETURN => $idSalesReturn,
        ]);

        $orderItemManualEvents = $this->getFactory()->getOmsFacade()->getOrderItemManualEvents($orderItemFilterTransfer);

        return [
            'return' => $returnTransfer,
            'customer' => $customerResponseTransfer->getCustomerTransfer(),
            'uniqueOrderReferences' => $returnExtractor->extractUniqueOrderReferencesFromReturn($returnTransfer),
            'uniqueItemStateLabels' => $returnExtractor->extractUniqueItemStateLabelsFromReturn($returnTransfer),
            'triggerButtonRedirectUrl' => $triggerButtonRedirectUrl,
            'orderItemManualEvents' => $orderItemManualEvents,
            'uniqueOrderItemManualEvents' => $this->extractUniqueOrderItemManualEvents($orderItemManualEvents),
            'salesOrderItemIds' => $salesOrderItemIds,
        ];
    }

    /**
     * @param string[][] $orderItemManualEventsGroupedByItem
     *
     * @return string[]
     */
    protected function extractUniqueOrderItemManualEvents(array $orderItemManualEventsGroupedByItem): array
    {
        $allOrderItemManualEvents = [];

        foreach ($orderItemManualEventsGroupedByItem as $orderItemManualEvents) {
            $allOrderItemManualEvents = array_merge($allOrderItemManualEvents, $orderItemManualEvents);
        }

        return array_unique($allOrderItemManualEvents);
    }
}
