<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface getRepository()
 */
class MethodController extends AbstractController
{
    public const ID_METHOD_PARAMETER = 'id-method';

    public const MESSAGE_UPDATE_SUCCESS = 'Shipment method "%s" was updated successfully.';
    public const MESSAGE_CREATE_SUCCESS = 'Shipment method "%s" was created successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createMethodFormDataProvider();

        $form = $this->getFactory()
            ->createMethodForm($dataProvider->getData(), $dataProvider->getOptions())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shipmentMethodTransfer = $form->getData();
            $this->getFacade()->createMethod($shipmentMethodTransfer);

            $this->addSuccessMessage(static::MESSAGE_CREATE_SUCCESS, ['%s' => $shipmentMethodTransfer->getName()]);

            return $this->redirectResponse('/shipment');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $idMethod = $this->castId($request->query->get(self::ID_METHOD_PARAMETER));

        if ($this->getFacade()->hasMethod($idMethod)) {
            $dataProvider = $this->getFactory()->createMethodFormDataProvider();

            $form = $this->getFactory()
                ->createMethodForm($dataProvider->getData($idMethod), $dataProvider->getOptions())
                ->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $shipmentMethodTransfer = $form->getData();

                $this->getFacade()
                    ->updateMethod($shipmentMethodTransfer);
                $this->addSuccessMessage(static::MESSAGE_UPDATE_SUCCESS, ['%s' => $shipmentMethodTransfer->getName()]);

                return $this->redirectResponse('/shipment');
            }

            return $this->viewResponse([
                'form' => $form->createView(),
            ]);
        }

        return $this->redirectResponse('/shipment');
    }
}
