<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Controller\MerchantShipment\MerchantShipmentController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\MerchantSalesOrderMerchantUserGuiCommunicationFactory getFactory()
 */
class MerchantShipmentCreateController extends MerchantShipmentController
{
    protected const MESSAGE_SHIPMENT_CREATE_SUCCESS = 'Shipment has been successfully created.';
    protected const MESSAGE_SHIPMENT_CREATE_FAIL = 'Shipment has not been created.';

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

        if (!$merchantOrderTransfer) {
            $this->addErrorMessage(static::MESSAGE_ORDER_NOT_FOUND_ERROR, ['%d' => $idMerchantSalesOrder]);
            $redirectUrl = Url::generate(static::REDIRECT_URL_DEFAULT)->build();

            return $this->redirectResponse($redirectUrl);
        }

        $dataProvider = $this->getFactory()->createMerchantShipmentGroupFormDataProvider();
        $form = $this->getFactory()
            ->createMerchantShipmentGroupForm(
                $dataProvider->getData($merchantOrderTransfer, $this->findShipment($merchantOrderTransfer->getMerchantReference())),
                $dataProvider->getOptions($merchantOrderTransfer)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $responseTransfer = $this->saveMerchantOrderShipment($form, $merchantOrderTransfer);

            if ($responseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(static::MESSAGE_SHIPMENT_CREATE_SUCCESS);
            }

            $this->addErrorMessage(static::MESSAGE_SHIPMENT_CREATE_FAIL);

            $redirectUrl = Url::generate(
                static::REDIRECT_URL_DEFAULT,
                [static::PARAM_ID_MERCHANT_SALES_ORDER => $merchantOrderTransfer->getIdMerchantOrder()]
            )->build();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'idMerchantSalesOrder' => $idMerchantSalesOrder,
            'merchantOrder' => $merchantOrderTransfer,
            'groupedMerchantOrderItems' => $this->groupMerchantOrderItemsByIdSalesOrderItem($merchantOrderTransfer),
            'form' => $form->createView(),
        ]);
    }
}
