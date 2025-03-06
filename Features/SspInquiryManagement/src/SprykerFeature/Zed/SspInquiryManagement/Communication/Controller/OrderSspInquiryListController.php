<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Communication\SspInquiryManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface getRepository()
 */
class OrderSspInquiryListController extends AbstractGatewayController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function indexAction(Request $request): array
    {
         $sspInquiryTable = $this
            ->getFactory()
            ->createOrderSspInquiryTable((new OrderTransfer())->setIdSalesOrder($request->query->getInt('id-sales-order')));

        return ['sspInquiryTable' => $sspInquiryTable->render()];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
         $sspInquiryTable = $this
            ->getFactory()
            ->createOrderSspInquiryTable((new OrderTransfer())->setIdSalesOrder($request->query->getInt('id-sales-order')));

        return $this->jsonResponse(
            $sspInquiryTable->fetchData(),
        );
    }
}
