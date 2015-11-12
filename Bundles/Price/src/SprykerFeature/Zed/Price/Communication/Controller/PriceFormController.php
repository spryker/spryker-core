<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Price\Communication\Controller;

use Generated\Shared\Transfer\PriceProductTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\Price\Communication\PriceDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method PriceDependencyContainer getDependencyContainer()
 * @method PriceFacade getFacade()
 */
class PriceFormController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function priceAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getPriceForm($request);

        if ($form->isValid()) {
            $transferPriceProduct = new PriceProductTransfer();
            $transferPriceProduct->fromArray($form->getRequestData());

            if ($transferPriceProduct->getIdPriceProduct() === null) {
                $this->getFacade()->createPriceForProduct($transferPriceProduct);
            } else {
                $this->getFacade()->setPriceForProduct($transferPriceProduct);
            }
        }

        return $this->jsonResponse($form->toArray());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function priceTypeAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getPriceTypeForm($request);

        if ($form->isValid()) {
            $data = $form->getRequestData();
            $this->getFacade()->createPriceType($data['name']);
        }

        return $this->jsonResponse($form->toArray());
    }

}
