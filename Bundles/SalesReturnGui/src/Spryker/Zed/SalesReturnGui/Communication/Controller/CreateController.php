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
    /**
     * @var string
     */
    protected const PARAM_ID_RETURN = 'id-return';

    /**
     * @var string
     */
    protected const PARAM_ID_ORDER = 'id-order';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\IndexController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_RETURN_LIST = '/sales-return-gui';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\DetailController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_RETURN_DETAIL = '/sales-return-gui/detail';

    /**
     * @var string
     */
    protected const MESSAGE_RETURN_CREATE_FAIL = 'Return has not been created.';

    /**
     * @var string
     */
    protected const MESSAGE_ORDER_NOT_FOUND = 'Order with id "%id%" was not found.';

    /**
     * @var string
     */
    protected const MESSAGE_RETURN_CREATED = 'Return was successfully created.';

    /**
     * @var string
     */
    protected const MESSAGE_PARAM_ID = '%id%';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
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
     * @param \Symfony\Component\Form\FormInterface|mixed[] $returnCreateForm
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
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

        return $this->provideTemplateData($returnCreateForm, $orderTransfer);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $returnCreateForm
     *
     * @param \Symfony\Component\Form\FormInterface $returnCreateForm
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<string, mixed>
     */
    protected function provideTemplateData(FormInterface $returnCreateForm, OrderTransfer $orderTransfer): array
    {
        return [
            'returnCreateForm' => $returnCreateForm->createView(),
            'order' => $orderTransfer,
            'templates' => $this->getFactory()
                ->createReturnCreateTemplateProvider()
                ->provide($returnCreateForm, $orderTransfer),
        ];
    }
}
