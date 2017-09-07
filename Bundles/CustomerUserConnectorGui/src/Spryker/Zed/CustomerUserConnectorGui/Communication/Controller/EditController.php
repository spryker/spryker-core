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
 * @method \Spryker\Zed\CustomerUserConnectorGui\Business\CustomerUserConnectorGuiFacadeInterface getFacade()
 */
class EditController extends AbstractController
{

    const PARAM_ID_USER = 'id-user';

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

            $this->getFacade()->updateCustomerUserConnection($formData);
            $this->addSuccessMessage('Customer-user connections are updated.');

            return $this->redirectResponse(
                sprintf(
                    '/customer-user-connector-gui/edit?%s=%d',
                    static::PARAM_ID_USER,
                    $idUser
                )
            );
        }

        $userTransfer = (new UserTransfer())->setIdUser($idUser);
        return $this->viewResponse([
            'availableCustomers' => $this->getFactory()->createAvailableCustomerTable($userTransfer)->render(),
            'assignedCustomers' => $this->getFactory()->createAssignedCustomerTable($userTransfer)->render(),
            'userTransfer' => $userTransfer,
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
