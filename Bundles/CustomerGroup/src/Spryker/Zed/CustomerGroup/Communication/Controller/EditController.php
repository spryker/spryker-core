<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacade getFacade()
 * @method \Spryker\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{

    const PARAM_ID_CUSTOMER_GROUP = 'id-customer-group';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCustomerGroup = $this->castId($request->query->get(static::PARAM_ID_CUSTOMER_GROUP));

        $dataProvider = $this->getFactory()->createCustomerGroupFormDataProvider();
        $form = $this->getFactory()
            ->createCustomerGroupForm(
                $dataProvider->getData($idCustomerGroup),
                $dataProvider->getOptions($idCustomerGroup)
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $customerGroupTransfer = new CustomerGroupTransfer();
            $customerGroupTransfer->fromArray($form->getData(), true);

            $this->getFacade()->update($customerGroupTransfer);

            return $this->redirectResponse(
                sprintf('/customer-group/view?%s=%d', static::PARAM_ID_CUSTOMER_GROUP, $idCustomerGroup)
            );
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idCustomerGroup' => $idCustomerGroup,
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
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createAddressTransfer()
    {
        return new AddressTransfer();
    }

    /**
     * @param int $idCustomer
     * @param int $defaultBillingAddress
     *
     * @return void
     */
    protected function updateBillingAddress($idCustomer, $defaultBillingAddress)
    {
        $addressTransfer = $this->createCustomAddressTransfer($idCustomer, $defaultBillingAddress);

        if ($this->isValidAddressTransfer($addressTransfer) === false) {
            return;
        }

        $this->getFacade()->setDefaultBillingAddress($addressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return bool
     */
    protected function isValidAddressTransfer(AddressTransfer $addressTransfer)
    {
        return (empty($addressTransfer->getIdCustomerAddress()) === false && $addressTransfer->getFkCustomer() !== null);
    }

    /**
     * @param int $idCustomer
     * @param int $defaultShippingAddress
     *
     * @return void
     */
    protected function updateShippingAddress($idCustomer, $defaultShippingAddress)
    {
        $addressTransfer = $this->createCustomAddressTransfer($idCustomer, $defaultShippingAddress);

        if ($this->isValidAddressTransfer($addressTransfer) === false) {
            return;
        }

        $this->getFacade()->setDefaultShippingAddress($addressTransfer);
    }

    /**
     * @param int $idCustomer
     * @param int $billingAddress
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createCustomAddressTransfer($idCustomer, $billingAddress)
    {
        $addressTransfer = $this->createAddressTransfer();

        $addressTransfer->setIdCustomerAddress($billingAddress);
        $addressTransfer->setFkCustomer($idCustomer);

        return $addressTransfer;
    }

}
