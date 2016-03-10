<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Controller;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacade getFacade()
 */
class CarrierController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()->createCarrierForm();
        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();
            $carrierTransfer = new ShipmentCarrierTransfer();
            $carrierTransfer->fromArray($data, true);
            $this->getFacade()
                ->createCarrier($carrierTransfer);

            return $this->redirectResponse('/shipment');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

}
