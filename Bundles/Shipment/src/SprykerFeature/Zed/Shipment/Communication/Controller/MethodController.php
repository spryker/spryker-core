<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Controller;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Shipment\Business\ShipmentFacade;
use SprykerFeature\Zed\Shipment\Communication\ShipmentDependencyContainer;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method ShipmentDependencyContainer getDependencyContainer()
 * @method ShipmentFacade getFacade()
 */
class MethodController extends AbstractController
{

    /**
     * @return Response
     */
    public function addAction()
    {
        $form = $this->getDependencyContainer()
            ->createMethodForm()
        ;
        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();
            $methodTransfer = new ShipmentMethodTransfer();
            $methodTransfer->fromArray($data, true);
            $this->getFacade()
                ->createMethod($methodTransfer)
            ;

            return $this->redirectResponse('/shipment/');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}
