<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Communication\Table\OrdersTable;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class IndexController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $table = $this->getDependencyContainer()->createOrdersTable();

        return [
            'orders' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createOrdersTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

}
