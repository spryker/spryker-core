<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Stock\Communication\Controller;

use Generated\Shared\Transfer\TypeTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Stock\Communication\StockDependencyContainer;
use SprykerFeature\Zed\Stock\Business\StockFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method StockDependencyContainer getDependencyContainer()
 * @method StockFacade getFacade()
 */
class FormController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function stockAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getStockForm($request);

        $form->init();

        if ($form->isValid()) {
            $stockTypeTransfer = new TypeTransfer();
            $stockTypeTransfer->fromArray($form->getRequestData());

            if (null === $stockTypeTransfer->getIdStock()) {
                $this->getFacade()->createStockType($stockTypeTransfer);
            } else {
                $this->getFacade()->updateStockType($stockTypeTransfer);
            }
            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function stockProductAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getStockProductForm($request);

        $form->init();

        if ($form->isValid()) {
            $stockProduct = new \Generated\Shared\Transfer\StockProductTransfer();
            $stockProduct->fromArray($form->getRequestData());

            if (null === $stockProduct->getIdStockProduct()) {
                $this->getStockFacade()->createStockProduct($stockProduct);
            } else {
                $this->getStockFacade()->updateStockProduct($stockProduct);
            }
            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->renderData());
    }

}
