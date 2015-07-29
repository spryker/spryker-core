<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Controller;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Shipment\Business\ShipmentFacade;
use SprykerFeature\Zed\Shipment\Communication\ShipmentDependencyContainer;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method ShipmentDependencyContainer getDependencyContainer()
 * @method ShipmentFacade getFacade()
 */
class CarrierController extends AbstractController
{

    /**
     * @return Response
     */
    public function addAction()
    {
        $form = $this->getDependencyContainer()
            ->createCarrierForm()
        ;
        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();
            $carrierTransfer = new ShipmentCarrierTransfer();
            $carrierTransfer->fromArray($data, true);
            $this->getFacade()
                ->createCarrier($carrierTransfer)
            ;

            return $this->redirectResponse('/shipment/');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}
