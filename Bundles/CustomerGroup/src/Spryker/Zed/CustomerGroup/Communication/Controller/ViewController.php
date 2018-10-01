<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Controller;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{
    public const PARAM_ID_CUSTOMER_GROUP = 'id-customer-group';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCustomerGroup = $request->get(static::PARAM_ID_CUSTOMER_GROUP);

        $customerGroupTransfer = $this->createCustomerGroupTransfer();
        $customerGroupTransfer->setIdCustomerGroup($idCustomerGroup);

        $customerGroupTransfer = $this->getFacade()
            ->get($customerGroupTransfer);

        $customerGroupArray = $customerGroupTransfer->toArray();

        $customerTable = $this->getFactory()
            ->createCustomerTable($customerGroupTransfer);

        return $this->viewResponse([
            'customerGroup' => $customerGroupArray,
            'idCustomerGroup' => $idCustomerGroup,
            'customerTable' => $customerTable->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idCustomerGroup = $this->castId($request->query->getInt(static::PARAM_ID_CUSTOMER_GROUP));

        $customerGroupTransfer = $this->createCustomerGroupTransfer();
        $customerGroupTransfer->setIdCustomerGroup($idCustomerGroup);

        $table = $this->getFactory()
            ->createCustomerTable($customerGroupTransfer);

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    protected function createCustomerGroupTransfer()
    {
        return new CustomerGroupTransfer();
    }
}
