<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Controller\MerchantShipment\AbstractMerchantShipmentController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\MerchantSalesOrderMerchantUserGuiCommunicationFactory getFactory()
 */
class MerchantShipmentCreateController extends AbstractMerchantShipmentController
{
    protected const MESSAGE_SHIPMENT_CREATE_SUCCESS = 'Shipment has been successfully created.';
    protected const MESSAGE_SHIPMENT_CREATE_FAIL = 'Shipment has not been created.';
    protected const MESSAGE_MERCHANT_REFERENCE_NOT_FOUND = 'Merchant reference not found.';
    protected const MESSAGE_SHIPMENT_NOT_FOUND = 'Shipment for merchant reference %s not found.';

    /**
     * @phpstan-return array<mixed>|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idMerchantSalesOrder = $this->castId($request->query->get(static::PARAM_ID_MERCHANT_SALES_ORDER));
        $merchantOrderTransfer = $this->findMerchantOrder($idMerchantSalesOrder);

        if (!$merchantOrderTransfer || !$merchantOrderTransfer->getOrder()) {
            $this->addErrorMessage(static::MESSAGE_ORDER_NOT_FOUND_ERROR, ['%d' => $idMerchantSalesOrder]);
            $redirectUrl = Url::generate(static::REDIRECT_URL_DEFAULT)->build();

            return $this->redirectResponse($redirectUrl);
        }

        $dataProvider = $this->getFactory()->createMerchantShipmentGroupFormDataProvider();

        if (!$merchantOrderTransfer->getMerchantReference()) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_REFERENCE_NOT_FOUND);
            $redirectUrl = Url::generate(static::REDIRECT_URL_DEFAULT)->build();

            return $this->redirectResponse($redirectUrl);
        }

        $shipmentTransfer = $this->findShipment($merchantOrderTransfer->getMerchantReference());

        if (!$shipmentTransfer) {
            $this->addErrorMessage(static::MESSAGE_SHIPMENT_NOT_FOUND, ['%s' => $merchantOrderTransfer->getMerchantReference()]);
            $redirectUrl = Url::generate(static::REDIRECT_URL_DEFAULT)->build();

            return $this->redirectResponse($redirectUrl);
        }

        $form = $this->getFactory()
            ->createMerchantShipmentGroupForm(
                $dataProvider->getData($merchantOrderTransfer, $shipmentTransfer),
                $dataProvider->getOptions($merchantOrderTransfer)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $responseTransfer = $this->saveMerchantOrderShipment($form, $merchantOrderTransfer);

            if ($responseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(static::MESSAGE_SHIPMENT_CREATE_SUCCESS);

                $redirectUrl = Url::generate(
                    static::REDIRECT_URL_DEFAULT,
                    [static::PARAM_ID_MERCHANT_SALES_ORDER => $merchantOrderTransfer->getIdMerchantOrder()]
                )->build();

                return $this->redirectResponse($redirectUrl);
            }

            $this->addErrorMessage(static::MESSAGE_SHIPMENT_CREATE_FAIL);
        }

        return $this->viewResponse([
            'idMerchantSalesOrder' => $idMerchantSalesOrder,
            'merchantOrder' => $merchantOrderTransfer,
            'groupedMerchantOrderItems' => $this->groupMerchantOrderItemsByIdSalesOrderItem($merchantOrderTransfer),
            'form' => $form->createView(),
        ]);
    }
}
