<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\Communication\SalesDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method SalesDependencyContainer getCommunicationFactory()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getCommunicationFactory()->createOrdersTable();

        return [
            'orders' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getCommunicationFactory()->createOrdersTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

}
