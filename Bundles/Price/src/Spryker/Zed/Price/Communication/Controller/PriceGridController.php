<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Price\Communication\PriceDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method PriceDependencyContainer getCommunicationFactory()
 */
class PriceGridController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function priceAction(Request $request)
    {
        $grid = $this->getCommunicationFactory()->getPriceGrid($request);
        $data = $grid->toArray();
        $data['rows'] = $this->orderData($data['rows']);

        return $this->jsonResponse($grid->toArray());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function priceTypeAction(Request $request)
    {
        $grid = $this->getCommunicationFactory()->getPriceTypeGrid($request);

        return $this->jsonResponse($grid->toArray());
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function orderData(array $data)
    {
        foreach ($data as $index => $row) {
            if ($row['sku_product_concrete'] !== null) {
                $data[$index]['sku_product'] = $row['sku_product_concrete'];
                $data[$index]['abstract'] = false;
            } else {
                $data[$index]['sku_product'] = $row['sku_product_abstract'];
                $data[$index]['abstract'] = true;
            }
            unset($data[$index]['sku_product_abstract']);
            unset($data[$index]['sku_product_concrete']);
        }

        return $data;
    }

}
