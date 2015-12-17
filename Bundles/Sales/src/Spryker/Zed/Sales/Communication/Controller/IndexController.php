<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\Communication\SalesCommunicationFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method SalesCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createOrdersTable();

        return [
            'orders' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createOrdersTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

}
