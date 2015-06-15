<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class GridController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function indexAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->getSalesGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function ordersAction(Request $request)
    {
        $grid = $this->getDependencyContainer()
            ->getOrdersItemsGridByOrderId($request)
        ;

        $data = $grid->renderData();

        // @todo check angularjs compatibility
        $totalItems = 0;
        $totalPrice = 0;

        foreach ($data['content']['rows'] as $item) {
            $totalItems += $item['qty'];
            $totalPrice += $item['price_to_pay'] * $item['qty'];
        }

        $data['content']['total'] = [
            'total_price_to_pay' => $totalPrice,
            'total_qty' => $totalItems,
        ];

        return $this->jsonResponse($data);
    }

    public function commentsAction(Request $request)
    {
        $grid = $this->getDependencyContainer()
            ->getCommentsGridByOrderId($request)
        ;

        $gridData = $grid->renderData();

        if (count($gridData['content']['rows']) > 0) {
            foreach ($gridData['content']['rows'] as &$row) {
//                $row['updated_at'] = $row['created_at'];
//                $row['created_at'] = $row['created_at']->getTimestamp();
//                var_dump($row['created_at']);die;
            }
        }

        return $this->jsonResponse($gridData);
    }
}
