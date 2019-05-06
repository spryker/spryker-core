<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\ShipmentGui\Communication\Form\Item\ItemForm;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentFormCreate;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    protected const REDIRECT_URL_DEFAULT = '/sales/detail';

    protected const MESSAGE_SHIPMENT_CREATE_SUCCESS = 'Shipment has been successfully created.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $request->query->get(SalesConfig::PARAM_ID_SALES_ORDER);

        $dataProvider = $this->getFactory()->createShipmentFormCreateDataProvider();

        $form = $this->getFactory()
            ->createShipmentFormCreate(
                $dataProvider->getData($idSalesOrder),
                $dataProvider->getOptions($idSalesOrder, null)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shipmentGroupTransfer = $this->createShipmentGroupTransfer($form->getData());
            $orderTransfer = $this
                ->getFactory()
                ->getSalesFacade()
                ->findOrderByIdSalesOrder($idSalesOrder);

            $responseTransfer = $this->getFactory()
                ->getShipmentFacade()
                ->saveShipment($shipmentGroupTransfer, $orderTransfer);

            if ($responseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(static::MESSAGE_SHIPMENT_CREATE_SUCCESS);
            }

            $redirectUrl = Url::generate(
                static::REDIRECT_URL_DEFAULT,
                [SalesConfig::PARAM_ID_SALES_ORDER => $idSalesOrder]
            )->build();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'idSalesOrder' => $idSalesOrder,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function createShipmentGroupTransfer(array $formData): ShipmentGroupTransfer
    {
        $shipmentGroupTransfer = new ShipmentGroupTransfer();
        $shipmentGroupTransfer->setShipment($this->createShipmentTransfer($formData));
        $shipmentGroupTransfer->setItems($this->createItemTransferList($shipmentGroupTransfer->getShipment(), $formData));

        return $shipmentGroupTransfer;
    }

    /**
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function createShipmentTransfer(array $formData): ShipmentTransfer
    {
        $shipmentTransfer = (new ShipmentTransfer())->fromArray($formData, true);
        $shipmentTransfer->setMethod($this->createShipmentMethodTransfer($formData[ShipmentFormCreate::FIELD_ID_SHIPMENT_METHOD]));

        if ($formData[ShipmentFormCreate::FIELD_ID_SHIPMENT_ADDRESS]) {
            $this->mapCustomerAddressToShippingAddress($shipmentTransfer, $formData[ShipmentFormCreate::FIELD_ID_SHIPMENT_ADDRESS]);
        }

        return $shipmentTransfer;
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function createShipmentMethodTransfer(int $idShipmentMethod): ?ShipmentMethodTransfer
    {
        return $this->getFactory()
            ->getShipmentFacade()
            ->findMethodById($idShipmentMethod);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param int $idCustomerAddress
     *
     * @return void
     */
    protected function mapCustomerAddressToShippingAddress(ShipmentTransfer $shipmentTransfer, int $idCustomerAddress): void
    {
        $addressTransfer = $this->getFactory()
            ->getCustomerFacade()
            ->findCustomerAddressById($idCustomerAddress);

        $shipmentTransfer->setShippingAddress($addressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject
     */
    protected function createItemTransferList(ShipmentTransfer $shipmentTransfer, array $formData): ArrayObject
    {
        $itemTransfers = new ArrayObject();
        foreach ($formData[ShipmentFormCreate::FORM_SALES_ORDER_ITEMS] as $itemTransfer) {
            if ($itemTransfer[ItemForm::FIELD_IS_UPDATED] === true) {
                $itemTransfer->setShipment($shipmentTransfer);
                $itemTransfers->append($itemTransfer);
            }
        }

        return $itemTransfers;
    }
}
