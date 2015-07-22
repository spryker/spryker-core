<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Controller;

use Generated\Zed\Ide\Shipment;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Shipment\Communication\Table\ShipmentMethodTable;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethod;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends AbstractController
{

    public function indexAction()
    {
        /** @var ShipmentMethodTable $table */
        $table = $this->getDependencyContainer()
            ->createShipmentMethodTable()
        ;
        $table->init();

        return $this->viewResponse([
            'shipmentMethodTable' => $table,
        ]);

    }
}
