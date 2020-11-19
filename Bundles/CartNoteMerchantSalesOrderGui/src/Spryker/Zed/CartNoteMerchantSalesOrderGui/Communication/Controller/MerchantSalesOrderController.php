<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNoteMerchantSalesOrderGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\CartNoteMerchantSalesOrderGui\Communication\CartNoteMerchantSalesOrderGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CartNoteMerchantSalesOrderGui\Business\CartNoteMerchantSalesOrderGuiFacadeInterface getFacade()
 */
class MerchantSalesOrderController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request): Response
    {
        /** @var \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer */
        $merchantOrderTransfer = $request->request->get('merchantOrderTransfer');

        return $this->renderView('@CartNote/Sales/list.twig', [
            'order' => $merchantOrderTransfer->getOrder(),
        ]);
    }
}
