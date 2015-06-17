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
