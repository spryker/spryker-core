<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $request->get(CustomerConstants::PARAM_ID_CUSTOMER);

        if (empty($idCustomer)) {
            return $this->redirectResponse('/customer');
        }

        $idCustomer = $this->castId($idCustomer);

        $customerTransfer = $this->loadCustomerTransfer($idCustomer);

        $addresses = $customerTransfer->getAddresses();

        $table = $this->getFactory()
            ->createCustomerAddressTable($idCustomer);

        return $this->viewResponse([
            'customer' => $customerTransfer,
            'addresses' => $addresses,
            'idCustomer' => $idCustomer,
            'addressTable' => $table->render(),

        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer()
    {
        return new CustomerTransfer();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idCustomer = $this->castId($request->get(CustomerConstants::PARAM_ID_CUSTOMER));

        $table = $this->getFactory()
            ->createCustomerAddressTable($idCustomer);

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function loadCustomerTransfer($idCustomer)
    {
        $customerTransfer = $this->createCustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);
        $customerTransfer = $this->getFacade()->getCustomer($customerTransfer);
        $customerTransfer = $this->applyCustomerTransferExpanderPlugins($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function applyCustomerTransferExpanderPlugins(CustomerTransfer $customerTransfer)
    {
        $expanderPlugins = $this->getFactory()->getCustomerTransferExpanderPlugins();
        foreach ($expanderPlugins as $expanderPlugin) {
            $customerTransfer = $expanderPlugin->expandTransfer($customerTransfer);
        }

        return $customerTransfer;
    }
}
