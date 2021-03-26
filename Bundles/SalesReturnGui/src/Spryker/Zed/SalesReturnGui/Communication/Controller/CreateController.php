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
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    protected const PARAM_ID_RETURN = 'id-return';
    protected const PARAM_ID_ORDER = 'id-order';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\IndexController::indexAction()
     */
    protected const ROUTE_RETURN_LIST = '/sales-return-gui';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\DetailController::indexAction()
     */
    protected const ROUTE_RETURN_DETAIL = '/sales-return-gui/detail';

    protected const MESSAGE_RETURN_CREATE_FAIL = 'Return has not been created.';
    protected const MESSAGE_ORDER_NOT_FOUND = 'Order with id "%id%" was not found.';
    protected const MESSAGE_RETURN_CREATED = 'Return was successfully created.';
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

        return $this->provideTemplateData($returnCreateForm, $orderTransfer);
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
                static::PARAM_ID_RETURN => $returnResponseTransfer->getReturnOrFail()->getIdSalesReturn(),
            ]);

            return $this->redirectResponse($redirectUrl);
        }

        $this->addErrorMessage(static::MESSAGE_RETURN_CREATE_FAIL);

        return $this->provideTemplateData($returnCreateForm, $orderTransfer);
    }

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \Symfony\Component\Form\FormInterface $returnCreateForm
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function provideTemplateData(FormInterface $returnCreateForm, OrderTransfer $orderTransfer): array
    {
        return [
            'order' => $orderTransfer,
            'templates' => $this->getFactory()
                ->createReturnCreateTemplateProvider()
                ->provide($returnCreateForm, $orderTransfer),
        ];
    }
}
