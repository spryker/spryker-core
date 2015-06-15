<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class DetailsController extends AbstractController
{
    public function indexAction(Request $request)
    {
        $orderId = $request->get('id');

//        $userDetails = $this->

            $this->getDependencyContainer()
//            ->getUserDetailsForOrder($orderId)
            ->createDetailsPage()
        ;



        return [
            'order_id' => $orderId,
        ];
    }
}
