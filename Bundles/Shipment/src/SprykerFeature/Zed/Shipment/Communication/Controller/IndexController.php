<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Shipment\Business\ShipmentFacade;
use SprykerFeature\Zed\Shipment\Communication\ShipmentDependencyContainer;

/**
 * @method ShipmentDependencyContainer getDependencyContainer()
 * @method ShipmentFacade getFacade()
 */
class IndexController extends AbstractController
{

    public function indexAction()
    {
        $table = $this->getDependencyContainer()
            ->createMethodTable();
        $table->init();

        return $this->viewResponse(['methodTable' => $table]);
    }
}
