<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Communication\Controller;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CustomerUserConnectorGui\Communication\CustomerUserConnectorGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    public const PARAM_ID_USER = 'id-user';

    public const PAGE_EDIT = '/customer-user-connector-gui/edit';
    public const PAGE_EDIT_WITH_PARAMS = '/customer-user-connector-gui/edit?%s=%d';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idUser = $request->query->getInt(static::PARAM_ID_USER);

        $form = $this->getFactory()->createCustomerUserConnectorForm($idUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $this->getFactory()->getCustomerUserConnectorFacade()->updateCustomerUserConnection($formData);
            $this->addSuccessMessage('Customer-user connections are updated.');

            return $this->redirectResponse(sprintf(static::PAGE_EDIT_WITH_PARAMS, static::PARAM_ID_USER, $idUser));
        }

        $userTransfer = $this->getUserTransfer($idUser);
        return $this->viewResponse([
            'availableCustomers' => $this->getFactory()->createAvailableCustomerTable($userTransfer)->render(),
            'assignedCustomers' => $this->getFactory()->createAssignedCustomerTable($userTransfer)->render(),
            'userTransfer' => $userTransfer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getUserTransfer($idUser)
    {
        $userEntity = $this->getFactory()->getUserQueryContainer()->queryUserById($idUser)->findOne();
        $userTransfer = (new UserTransfer())->fromArray($userEntity->toArray(), true);

        return $userTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableCustomerTableAction(Request $request)
    {
        $idUser = $request->query->get(static::PARAM_ID_USER);

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
        $idUser = $request->query->get(static::PARAM_ID_USER);

        return $this->jsonResponse(
            $this->getFactory()->createAssignedCustomerTable((new UserTransfer())->setIdUser($idUser))->fetchData()
        );
    }
}
