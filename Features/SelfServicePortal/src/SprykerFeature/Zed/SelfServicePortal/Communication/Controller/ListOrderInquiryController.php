<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ListOrderInquiryController extends AbstractGatewayController
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

        return ['orderInquiryTable' => $sspInquiryTable->render()];
    }

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
