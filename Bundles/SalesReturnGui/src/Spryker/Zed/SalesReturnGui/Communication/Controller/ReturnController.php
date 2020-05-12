<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 */
class ReturnController extends AbstractController
{
    protected const PARAM_ID_ORDER = 'id-order';
    protected const PARAM_ID_RETURN = 'id-return';

    protected const ERROR_MESSAGE_RETURN_CREATE_FAIL = 'Return has not been created.';
    protected const ERROR_MESSAGE_ORDER_NOT_FOUND = 'Order with id "%id%" was not found.';
    protected const ERROR_MESSAGE_PARAM_ID = '%id%';

    protected const SUCCESS_MESSAGE_RETURN_CREATED = 'Return was successfully created.';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\ReturnController::indexAction()
     */
    protected const ROUTE_RETURN_LIST = '/sales-return-gui/return';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\ReturnController::detailAction()
     */
    protected const ROUTE_RETURN_DETAIL = '/sales-return-gui/return/detail';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        return [];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function detailAction(Request $request): array
    {
        return [];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function createAction(Request $request)
    {
        $response = $this->executeCreateAction($request);

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
    protected function executeCreateAction(Request $request)
    {
        $idOrder = $this->castId($request->get(static::PARAM_ID_ORDER));
        $orderTransfer = $this->getFactory()
            ->getSalesFacade()
            ->findOrderByIdSalesOrder($idOrder);

        if (!$orderTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_ORDER_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idOrder,
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
            $this->addSuccessMessage(static::SUCCESS_MESSAGE_RETURN_CREATED);

            $redirectUrl = Url::generate(static::ROUTE_RETURN_DETAIL, [
                static::PARAM_ID_RETURN => $returnResponseTransfer->getReturn()->getIdSalesReturn(),
            ]);

            return $this->redirectResponse($redirectUrl);
        }

        $this->addErrorMessage(static::ERROR_MESSAGE_RETURN_CREATE_FAIL);

        return [
            'returnCreateForm' => $returnCreateForm->createView(),
            'order' => $orderTransfer,
        ];
    }
}
