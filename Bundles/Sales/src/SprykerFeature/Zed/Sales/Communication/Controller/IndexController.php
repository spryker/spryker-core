<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->getSalesGrid($request);


        $table = $this->getDependencyContainer()->createOrdersTable();
        $table->init();
//
//        return $this->viewResponse([
//            'grid' => [
//                'content' => [
//                    'rows' => []
//                ],
//            ],
//            'orders' => $table,
//        ]);

        return [
            'orders' => $table,
//            'grid' => $grid->renderData(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createOrdersTable();
        $table->init();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

}
