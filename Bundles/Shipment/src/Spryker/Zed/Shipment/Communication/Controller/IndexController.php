<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Shipment\Business\ShipmentFacade;
use Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method ShipmentCommunicationFactory getFactory()
 * @method ShipmentFacade getFacade()
 */
class IndexController extends AbstractController
{

    public function indexAction()
    {
        $table = $this->getFactory()
            ->createMethodTable();

        return $this->viewResponse(['methodTable' => $table->render()]);
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createMethodTable();

        return $this->jsonResponse($table->fetchData());
    }

}
