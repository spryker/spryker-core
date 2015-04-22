<?php

namespace SprykerFeature\Zed\Price\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Price\Communication\PriceDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Price\Communication\Form\PriceForm;

/**
 * @method PriceDependencyContainer getDependencyContainer()
 */
class PriceFormController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function priceAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getPriceForm($request);

        $form->init();

        if ($form->isValid()) {
            $transferPriceProduct = $this->getLocator()->price()->transferProduct();
            $transferPriceProduct->fromArray($form->getRequestData());

            if (null == $transferPriceProduct->getIdPriceProduct()) {
                $this->getLocator()->price()->facade()->createPriceForProduct($transferPriceProduct);
            } else {
                $this->getLocator()->price()->facade()->setPriceForProduct($transferPriceProduct);
            }
        }

        return $this->jsonResponse($form->toArray());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function priceTypeAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getPriceTypeForm($request);

        $form->init();

        if ($form->isValid()) {
            $data = $form->getRequestData();
            $this->getLocator()->price()->facade()->createPriceType($data['name']);
        }

        return $this->jsonResponse($form->toArray());
    }
}
