<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui\Communication\Controller;

use Generated\Shared\Transfer\StockResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\StockGui\Communication\StockGuiCommunicationFactory getFactory()
 */
class EditWarehouseController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\StockGui\Communication\Controller\WarehouseController::listAction()
     */
    protected const REDIRECT_URL = '/stock-gui/warehouse/list';

    protected const MESSAGE_SUCCESS = 'Warehouse has been successfully updated';
    protected const MESSAGE_STOCK_NOT_FOUND = 'Stock not found';

    protected const PARAMETER_ID_STOCK = 'id-stock';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idStock = $this->castId($request->get(static::PARAMETER_ID_STOCK));
        $stockTransfer = $this->getFactory()->getStockFacade()->findStockById($idStock);
        if ($stockTransfer === null) {
            $this->addErrorMessage(static::MESSAGE_STOCK_NOT_FOUND);

            return $this->redirectResponse(static::REDIRECT_URL);
        }

        $stockTabs = $this->getFactory()->createStockTabs();

        $stockForm = $this->getFactory()
            ->getStockForm($stockTransfer)
            ->handleRequest($request);

        if ($stockForm->isSubmitted() && $stockForm->isValid()) {
            return $this->handleStockForm($stockForm);
        }

        return $this->viewResponse([
            'stockForm' => $stockForm->createView(),
            'stockTabs' => $stockTabs->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $stockForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleStockForm(FormInterface $stockForm): RedirectResponse
    {
        $stockResponseTransfer = $this->getFactory()
            ->getStockFacade()
            ->updateStock($stockForm->getData());

        if ($stockResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_SUCCESS);

            return $this->redirectResponse(static::REDIRECT_URL);
        }

        $this->setErrors($stockResponseTransfer);

        return $this->redirectResponse(static::REDIRECT_URL);
    }

    /**
     * @param \Generated\Shared\Transfer\StockResponseTransfer $stockResponseTransfer
     *
     * @return void
     */
    protected function setErrors(StockResponseTransfer $stockResponseTransfer): void
    {
        foreach ($stockResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }
}
