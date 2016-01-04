<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication\Controller;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Shipment\Business\ShipmentFacade;
use Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method ShipmentCommunicationFactory getFactory()
 * @method ShipmentFacade getFacade()
 */
class MethodController extends AbstractController
{

    const ID_METHOD_PARAMETER = 'id-method';

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()
            ->createMethodForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $methodTransfer = new ShipmentMethodTransfer();
            $methodTransfer->fromArray($data, true);
            $this->getFacade()
                ->createMethod($methodTransfer);

            $this->addSuccessMessage('Shipment method ' . $methodTransfer->getName() . ' saved');

            return $this->redirectResponse('/shipment/');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $idMethod = $request->query->get(self::ID_METHOD_PARAMETER);

        if ($this->getFacade()->hasMethod($idMethod)) {
            $form = $this->getFactory()
                ->createMethodForm($idMethod);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $methodTransfer = new ShipmentMethodTransfer();
                $methodTransfer->fromArray($data, true);

                $this->getFacade()
                    ->updateMethod($methodTransfer);
                $this->addSuccessMessage('Shipment method ' . $methodTransfer->getName() . ' updated');

                return $this->redirectResponse('/shipment/');
            }

            return $this->viewResponse([
                'form' => $form->createView(),
            ]);
        }

        return $this->redirectResponse('/shipment/');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idMethod = $request->query->get(self::ID_METHOD_PARAMETER);

        if ($this->getFacade()->hasMethod($idMethod)) {
            $this->getFacade()->deleteMethod($idMethod);
        }

        return $this->redirectResponse('/shipment/');
    }

}
