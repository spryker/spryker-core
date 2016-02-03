<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Communication\SalesCommunicationFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacade getFacade()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createOrdersTable($this->getFacade());

        return [
            'orders' => $table->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createOrdersTable($this->getFacade());

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

}
