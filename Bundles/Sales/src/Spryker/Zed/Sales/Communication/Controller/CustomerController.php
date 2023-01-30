<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use InvalidArgumentException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
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
        $customerTransfer = $this->getCustomerTransfer($request);
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
        $customerReference = (string)$request->query->get(SalesConfig::PARAM_CUSTOMER_REFERENCE);
        $ordersTable = $this->getFactory()->createCustomerOrdersTable($customerReference);

        return $this->jsonResponse($ordersTable->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransfer(Request $request): CustomerTransfer
    {
        // @deprecated Exists for BC reasons. Will be removed in the next major release.
        if ($request->request->has('customerTransfer')) {
            /** @phpstan-var \Generated\Shared\Transfer\CustomerTransfer */
            return $request->request->get('customerTransfer');
        }

        if (!$request->request->has('serializedCustomerTransfer')) {
            throw new InvalidArgumentException('`serializedCustomerTransfer` not found in request');
        }

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->unserialize((string)$request->request->get('serializedCustomerTransfer'));

        return $customerTransfer;
    }
}
