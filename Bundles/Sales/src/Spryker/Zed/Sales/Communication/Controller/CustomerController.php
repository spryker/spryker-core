<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 */

class CustomerController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function customerOrdersAction(Request $request)
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        $customerTransfer = $request->request->get('customerTransfer');
        $ordersTable = $this->getFactory()->createCustomerOrdersTable($customerTransfer->getCustomerReference());

        return [
            'ordersTable' => $ordersTable->render(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ordersTableAction(Request $request)
    {
        $customerReference = $request->query->get(SalesConfig::PARAM_CUSTOMER_REFERENCE);
        $ordersTable = $this->getFactory()->createCustomerOrdersTable($customerReference);

        return $this->jsonResponse($ordersTable->fetchData());
    }
}
