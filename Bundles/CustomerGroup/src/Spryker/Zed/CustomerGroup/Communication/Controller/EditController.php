<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    public const PARAM_ID_CUSTOMER_GROUP = 'id-customer-group';
    public const MESSAGE_CUSTOMER_GROUP_UPDATE_SUCCESS = 'Customer group was updated successfully.';

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

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer */
            $customerGroupTransfer = $form->getData();

            $this->getFacade()->update($customerGroupTransfer);

            $this->addSuccessMessage(static::MESSAGE_CUSTOMER_GROUP_UPDATE_SUCCESS);
            return $this->redirectResponse(
                sprintf('/customer-group/view?%s=%d', static::PARAM_ID_CUSTOMER_GROUP, $idCustomerGroup)
            );
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idCustomerGroup' => $idCustomerGroup,
            'customerGroupFormTabs' => $this->getFactory()->createCustomerGroupFormTabs()->createView(),
            'availableCustomerTable' => $this->getFactory()
                ->createAvailableCustomerTable($idCustomerGroup)
                ->render(),
            'assignedCustomerTable' => $this->getFactory()
                ->createAssignedCustomerTable($idCustomerGroup)
                ->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableCustomerTableAction(Request $request)
    {
        $idCustomerGroup = $this->castId($request->query->get(static::PARAM_ID_CUSTOMER_GROUP));
        $availableCustomerTable = $this->getFactory()
            ->createAvailableCustomerTable($idCustomerGroup);

        return $this->jsonResponse($availableCustomerTable->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedCustomerTableAction(Request $request)
    {
        $idCustomerGroup = $this->castId($request->query->get(static::PARAM_ID_CUSTOMER_GROUP));
        $assignedCustomerTable = $this->getFactory()
            ->createAssignedCustomerTable($idCustomerGroup);

        return $this->jsonResponse($assignedCustomerTable->fetchData());
    }
}
