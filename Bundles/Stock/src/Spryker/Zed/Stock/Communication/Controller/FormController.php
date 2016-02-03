<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Communication\Controller;

use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TypeTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Stock\Communication\StockCommunicationFactory getFactory()
 * @method \Spryker\Zed\Stock\Business\StockFacade getFacade()
 */
class FormController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function stockAction(Request $request)
    {
        $form = $this->getFactory()->getStockForm($request);

        if ($form->isValid()) {
            $stockTypeTransfer = new TypeTransfer();
            $stockTypeTransfer->fromArray($form->getRequestData());

            if ($stockTypeTransfer->getIdStock() === null) {
                $this->getFacade()->createStockType($stockTypeTransfer);
            } else {
                $this->getFacade()->updateStockType($stockTypeTransfer);
            }
            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function stockProductAction(Request $request)
    {
        $form = $this->getFactory()->getStockProductForm($request);

        if ($form->isValid()) {
            $stockProduct = new StockProductTransfer();
            $stockProduct->fromArray($form->getRequestData());

            if ($stockProduct->getIdStockProduct() === null) {
                $this->getStockFacade()->createStockProduct($stockProduct);
            } else {
                $this->getStockFacade()->updateStockProduct($stockProduct);
            }
            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->renderData());
    }

}
