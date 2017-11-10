<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Controller;

use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Zed\Acl\Business\Exception\UserAndGroupNotFoundException;
use Spryker\Zed\Acl\Communication\Form\GroupForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 */
class GroupController extends AbstractController
{
    const USER_LIST_URL = '/acl/users';
    const PARAMETER_ID_GROUP = 'id-group';
    const PARAMETER_ID_USER = 'id-user';

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createGroupTable();

        return $this->viewResponse([
            'table' => $table->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createGroupTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function addAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createGroupFormDataProvider();

        $form = $this->getFactory()
            ->createGroupForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $roles = $this->getRoleTransfersFromForm($formData);

            $groupTransfer = $this->getFacade()->addGroup(
                $formData[GroupForm::FIELD_TITLE],
                $roles
            );

            return $this->redirectResponse('/acl/group/edit?' . self::PARAMETER_ID_GROUP . '=' . $groupTransfer->getIdAclGroup());
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idAclGroup = $this->castId($request->query->get(self::PARAMETER_ID_GROUP));

        $dataProvider = $this->getFactory()->createGroupFormDataProvider();

        $form = $this->getFactory()
            ->createGroupForm(
                $dataProvider->getData($idAclGroup),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();
            $roles = $this->getRoleTransfersFromForm($formData);

            $groupTransfer = $this->getFacade()->getGroup($idAclGroup);
            $groupTransfer->setName($formData[GroupForm::FIELD_TITLE]);
            $groupTransfer = $this->getFacade()->updateGroup($groupTransfer, $roles);

            $url = sprintf('/acl/group/edit?%s=%d', self::PARAMETER_ID_GROUP, $groupTransfer->getIdAclGroup());

            return $this->redirectResponse($url);
        }

        $usersTable = $this->getFactory()->createGroupUsersTable($idAclGroup);

        return $this->viewResponse([
            'form' => $form->createView(),
            'users' => $usersTable->render(),
        ]);
    }

    /**
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    protected function getRoleTransfersFromForm(array $formData)
    {
        $roles = new RolesTransfer();

        foreach ($formData[GroupForm::FIELD_ROLES] as $idRole) {
            $roleTransfer = (new RoleTransfer())
                ->setIdAclRole($idRole);

            $roles->addRole($roleTransfer);
        }

        return $roles;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function usersAction(Request $request)
    {
        $idGroup = $this->castId($request->query->get(self::PARAMETER_ID_GROUP));

        $usersTable = $this->getFactory()->createGroupUsersTable($idGroup);

        return $this->jsonResponse(
            $usersTable->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteUserFromGroupAction(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_DELETE)) {
            throw new MethodNotAllowedHttpException([Request::METHOD_DELETE], 'This action requires a DELETE request.');
        }

        $idGroup = $this->castId($request->request->get(self::PARAMETER_ID_GROUP));
        $idUser = $this->castId($request->request->get(self::PARAMETER_ID_USER));

        try {
            $this->getFacade()->removeUserFromGroup($idUser, $idGroup);
            $this->addSuccessMessage('Deleted user from group');
        } catch (UserAndGroupNotFoundException $e) {
            $this->addErrorMessage('User and group not found');
        }

        return $this->redirectResponse('/acl/group/edit?id-group=' . $idGroup);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function rolesAction(Request $request)
    {
        $idGroup = $this->castId($request->get(self::PARAMETER_ID_GROUP));

        $roles = $this->getFactory()->getGroupRoleListByGroupId($idGroup);

        return $this->jsonResponse($roles);
    }
}
