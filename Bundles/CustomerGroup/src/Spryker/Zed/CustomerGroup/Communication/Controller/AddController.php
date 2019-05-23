<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupRepositoryInterface getRepository()
 */
class AddController extends AbstractController
{
    public const MESSAGE_CUSTOMER_GROUP_CREATE_SUCCESS = 'Customer group was created successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createCustomerGroupFormDataProvider();

        $form = $this->getFactory()
            ->createCustomerGroupForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer */
            $customerGroupTransfer = $form->getData();

            $this->getFacade()->add($customerGroupTransfer);

            $this->addSuccessMessage(static::MESSAGE_CUSTOMER_GROUP_CREATE_SUCCESS);

            return $this->redirectResponse('/customer-group');
        }

        return $this->viewResponse([
            'customerGroupFormTabs' => $this->getFactory()->createCustomerGroupFormTabs()->createView(),
            'form' => $form->createView(),
            'availableCustomerTable' => $this->getFactory()
                ->createAvailableCustomerTable()
                ->render(),
            'assignedCustomerTable' => $this->getFactory()
                ->createAssignedCustomerTable()
                ->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableCustomerTableAction()
    {
        $availableCustomerTable = $this->getFactory()
            ->createAvailableCustomerTable();

        return $this->jsonResponse($availableCustomerTable->fetchData());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedCustomerTableAction()
    {
        $assignedCustomerTable = $this->getFactory()
            ->createAssignedCustomerTable();

        return $this->jsonResponse($assignedCustomerTable->fetchData());
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer()
    {
        return new CustomerTransfer();
    }
}
