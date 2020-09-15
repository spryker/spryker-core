<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\Communication\MerchantSalesOrderGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrderGui\Business\MerchantSalesOrderGuiFacadeInterface getFacade()
 */
class MerchantShipmentCreateController extends AbstractController
{
    protected const PARAM_ID_MERCHANT_SALES_ORDER = 'id-merchant-sales-order';
    protected const PARAM_ID_SHIPMENT = 'id-shipment';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): void
    {
        $idMerchantSalesOrder = $request->query->getInt(static::PARAM_ID_MERCHANT_SALES_ORDER);
        $idShipment = $request->query->getInt(static::PARAM_ID_SHIPMENT);
        $merchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();

        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setIdMerchantOrder($idMerchantSalesOrder);

        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder($merchantOrderCriteriaTransfer);

        if ($merchantUserTransfer->getMerchant()->getMerchantReference() !== $merchantOrderTransfer->getMerchantReference()) {
            throw new AccessDeniedHttpException('Access denied');
        }

        $shipmentTransfer = $this->getFactory()->getShipmentFacade()->findShipmentById($idShipment);

        if (!$this->getFacade()->isMerchantOrderShipment($merchantOrderTransfer, $shipmentTransfer)) {
            throw new AccessDeniedHttpException('Access denied');
        }


    }
}
