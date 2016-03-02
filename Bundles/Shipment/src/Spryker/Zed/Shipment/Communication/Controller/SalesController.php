<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{

    /**
     * @return array
     */
    public function listAction()
    {
        $table = $this->getFactory()->createMethodTable();

        return $this->viewResponse([
            'methodTable' => $table->render(),
            'shipmentMethod' => [
                'carrierName' => 'DHL',
                'name' => 'dhl',
            ],
            'expenses' => [
                [
                    'type' => 'SHIPMENT_EXPENSE_TYPE',
                    'sumGrossPriceWithDiscounts' => '3000',
                ]
            ],
        ]);
    }

}
