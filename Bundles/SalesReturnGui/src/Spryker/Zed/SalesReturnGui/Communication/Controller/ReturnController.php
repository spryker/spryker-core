<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Controller;

use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 */
class ReturnController extends AbstractReturnController
{
    protected const PARAM_ID_ORDER = 'id-order';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\ReturnController::indexAction()
     */
    protected const ROUTE_RETURN_LIST = '/sales-return-gui/return';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\ReturnController::detailAction()
     */
    protected const ROUTE_RETURN_DETAIL = '/sales-return-gui/return/detail';

    protected const MESSAGE_RETURN_CREATE_FAIL = 'Return has not been created.';
    protected const MESSAGE_ORDER_NOT_FOUND = 'Order with id "%id%" was not found.';
    protected const MESSAGE_RETURN_CREATED = 'Return was successfully created.';
    protected const MESSAGE_RETURN_NOT_FOUND = 'Requested return with ID "%id%" was not found.';
    protected const MESSAGE_PARAM_ID = '%id%';

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
    protected function executeDetailAction(Request $request)
    {
        $idSalesReturn = $this->castId(
            $request->get(static::PARAM_ID_RETURN)
        );

        $returnTransfer = $this->findReturn($request);

        if (!$returnTransfer) {
            $this->addErrorMessage(static::MESSAGE_RETURN_NOT_FOUND, [
                static::MESSAGE_PARAM_ID => $idSalesReturn,
            ]);

            return $this->redirectResponse(
                Url::generate(static::ROUTE_RETURN_LIST)->build()
            );
        }

        $customerResponseTransfer = $this->getFactory()
            ->getCustomerFacade()
            ->findCustomerByReference($returnTransfer->getCustomerReference());

        $returnExtractor = $this->getFactory()->createReturnExtractor();

        $salesOrderItemIds = $returnExtractor->extractSalesOrderItemIdsFromReturn($returnTransfer);
        $orderItemFilterTransfer = (new OrderItemFilterTransfer())->setSalesOrderItemIds($salesOrderItemIds);

        $triggerButtonRedirectUrl = Url::generate(static::ROUTE_RETURN_DETAIL, [
            static::PARAM_ID_RETURN => $idSalesReturn,
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function createAction(Request $request)
    {
        $response = $this->executeCreateAction($request);

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
    protected function executeCreateAction(Request $request)
    {
        $idOrder = $this->castId($request->get(static::PARAM_ID_ORDER));
        $orderTransfer = $this->getFactory()
            ->getSalesFacade()
            ->findOrderByIdSalesOrder($idOrder);

        if (!$orderTransfer) {
            $this->addErrorMessage(static::MESSAGE_ORDER_NOT_FOUND, [
                static::MESSAGE_PARAM_ID => $idOrder,
            ]);

            return $this->redirectResponse(static::ROUTE_RETURN_LIST);
        }

        $returnCreateForm = $this->getFactory()
            ->getCreateReturnForm($orderTransfer)
            ->handleRequest($request);

        if ($returnCreateForm->isSubmitted() && $returnCreateForm->isValid()) {
            return $this->processReturnCreateForm($returnCreateForm, $orderTransfer);
        }

        return [
            'returnCreateForm' => $returnCreateForm->createView(),
            'order' => $orderTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $returnCreateForm
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processReturnCreateForm(FormInterface $returnCreateForm, OrderTransfer $orderTransfer)
    {
        $returnResponseTransfer = $this->getFactory()
            ->createReturnHandler()
            ->createReturn($returnCreateForm->getData(), $orderTransfer);

        if ($returnResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_RETURN_CREATED);

            $redirectUrl = Url::generate(static::ROUTE_RETURN_DETAIL, [
                static::PARAM_ID_RETURN => $returnResponseTransfer->getReturn()->getIdSalesReturn(),
            ]);

            return $this->redirectResponse($redirectUrl);
        }

        $this->addErrorMessage(static::MESSAGE_RETURN_CREATE_FAIL);

        return [
            'returnCreateForm' => $returnCreateForm->createView(),
            'order' => $orderTransfer,
        ];
    }
}
