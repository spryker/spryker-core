<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Controller;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacade getFacade()
 */
class CarrierController extends AbstractController
{
    const MESSAGE_SUCCESS_CREATE = 'Carrier created successfully';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $carrierFormDataProvider = $this
            ->getFactory()
            ->createCarrierFormDataProvider();

        $form = $this->getFactory()
            ->createCarrierForm(
                $carrierFormDataProvider->getData(),
                $carrierFormDataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $carrierTransfer = new ShipmentCarrierTransfer();
            $carrierTransfer->fromArray($data, true);
            $this->getFacade()
                ->createCarrier($carrierTransfer);

            $this->addSuccessMessage(static::MESSAGE_SUCCESS_CREATE);
            return $this->redirectResponse('/shipment');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}
