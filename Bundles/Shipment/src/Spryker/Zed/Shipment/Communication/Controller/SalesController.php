<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{

    /**
     * @param Request $request
     * @return array
     */
    public function listAction(Request $request)
    {
        return $this->viewResponse([
            'order' => $request->request->get('orderTransfer'),
        ]);
    }

}
