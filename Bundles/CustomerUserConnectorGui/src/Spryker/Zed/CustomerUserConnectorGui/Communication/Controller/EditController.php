<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Communication\Controller;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\CustomerUserConnectorGui\Communication\Form\CustomerUserConnectorForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CustomerUserConnectorGui\Communication\CustomerUserConnectorGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idUser = $request->query->getInt('id-user');

        $form = $this->getFactory()->createCustomerUserConnectorForm($idUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            foreach ($formData[CustomerUserConnectorForm::FIELD_IDS_USER_TO_ASSIGN_CSV] as $customerIdToAssign) {
                $customerEntity = $this->getFactory()->getCustomerQueryContainer()->queryCustomerById($customerIdToAssign)->findOne();
                $customerEntity->setFkUser($idUser);
                $customerEntity->save();
            }

            foreach ($formData[CustomerUserConnectorForm::FIELD_IDS_USER_TO_DE_ASSIGN_CSV] as $customerIdToDeAssign) {
                $customerEntity = $this->getFactory()->getCustomerQueryContainer()->queryCustomerById($customerIdToDeAssign)->findOne();
                $customerEntity->setFkUser(null);
                $customerEntity->save();
            }

            $this->addSuccessMessage('User updated.');

            return $this->redirectResponse('/customer-user-connector-gui/edit?id-user=' . $idUser);
        }

        return $this->viewResponse([
            'availableCustomers' => $this->getFactory()->createAvailableCustomerTable((new UserTransfer())->setIdUser($idUser))->render(),
            'assignedCustomers' => $this->getFactory()->createAssignedCustomerTable((new UserTransfer())->setIdUser($idUser))->render(),
            'idUser' => $idUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableCustomerTableAction(Request $request)
    {
        $idUser = $request->query->get('id-user');

        return $this->jsonResponse(
            $this->getFactory()->createAvailableCustomerTable((new UserTransfer())->setIdUser($idUser))->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedCustomerTableAction(Request $request)
    {
        $idUser = $request->query->get('id-user');

        return $this->jsonResponse(
            $this->getFactory()->createAssignedCustomerTable((new UserTransfer())->setIdUser($idUser))->fetchData()
        );
    }

}
