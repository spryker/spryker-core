<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Controller;

use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 */
class DetailController extends AbstractController
{
    protected const PARAM_ID_RETURN = 'id-return';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\IndexController::indexAction()
     */
    protected const ROUTE_RETURN_LIST = '/sales-return-gui';

    protected const MESSAGE_RETURN_NOT_FOUND = 'Requested return with ID "%id%" was not found.';
    protected const MESSAGE_PARAM_ID = '%id%';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $response = $this->executeIndexAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->viewResponse($response);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeIndexAction(Request $request)
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

        $returnExtractor = $this->getFactory()->createReturnExtractor();

        $salesOrderItemIds = $returnExtractor->extractSalesOrderItemIdsFromReturn($returnTransfer);
        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->setSalesOrderItemIds($salesOrderItemIds);

        $orderItemManualEvents = $this->getFactory()->getOmsFacade()->getOrderItemManualEvents($orderItemFilterTransfer);

        return [
            'return' => $returnTransfer,
            'customer' => $this->getFactory()->createCustomerReader()->getCustomerFromReturn($returnTransfer),
            'uniqueOrderReferences' => $returnExtractor->extractUniqueOrderReferencesFromReturn($returnTransfer),
            'uniqueItemStateLabels' => $returnExtractor->extractUniqueItemStateLabelsFromReturn($returnTransfer),
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
}
