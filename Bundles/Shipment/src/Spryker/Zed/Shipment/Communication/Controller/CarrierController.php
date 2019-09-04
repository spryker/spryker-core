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
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface getRepository()
 */
class CarrierController extends AbstractController
{
    public const MESSAGE_CARRIER_CREATE_SUCCESS = 'Carrier was created successfully.';

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

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $carrierTransfer = new ShipmentCarrierTransfer();
            $carrierTransfer->fromArray($data, true);
            $this->getFacade()
                ->createCarrier($carrierTransfer);

            $this->addSuccessMessage(static::MESSAGE_CARRIER_CREATE_SUCCESS);

            return $this->redirectResponse('/shipment');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}
