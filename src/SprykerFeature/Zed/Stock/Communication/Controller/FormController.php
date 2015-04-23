<?php

namespace SprykerFeature\Zed\Stock\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Stock\Communication\StockDependencyContainer;
use Pyz\Zed\Stock\Business\StockFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method StockDependencyContainer getDependencyContainer()
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
            $stockTypeTransfer = $this->getLocator()->stock()->transferStockType();
            $stockTypeTransfer->fromArray($form->getRequestData());

            if (null === $stockTypeTransfer->getIdStock()) {
                $this->getStockFacade()->createStockType($stockTypeTransfer);
            } else {
                $this->getStockFacade()->updateStockType($stockTypeTransfer);
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
            $stockProduct = $this->getLocator()->stock()->transferStockProduct();
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

    /**
     * @return StockFacade
     */
    protected function getStockFacade()
    {
        return $this->getLocator()->stock()->facade();
    }
}
