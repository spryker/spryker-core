<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Controller;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Shipment\Business\ShipmentFacade;
use SprykerFeature\Zed\Shipment\Communication\ShipmentDependencyContainer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method ShipmentDependencyContainer getDependencyContainer()
 * @method ShipmentFacade getFacade()
 */
class MethodController extends AbstractController
{

    const ID_METHOD_PARAMETER = 'id-method';

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

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $idMethod = $request->query->get(self::ID_METHOD_PARAMETER);

        if ($this->getFacade()->hasMethod($idMethod)) {
            $form = $this->getDependencyContainer()
                ->createMethodForm($idMethod)
            ;
            $form->handleRequest();

            if ($form->isValid()) {
                $data = $form->getData();
                $methodTransfer = new ShipmentMethodTransfer();
                $methodTransfer->fromArray($data, true);
                $this->getFacade()
                    ->updateMethod($methodTransfer)
                ;

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
