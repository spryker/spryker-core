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
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 */
class GroupController extends AbstractController
{
    public const GROUP_LIST_URL = '/acl/group';

    public const USER_LIST_URL = '/acl/users';
    public const PARAMETER_ID_GROUP = 'id-group';
    public const PARAMETER_ID_USER = 'id-user';

    public const MESSAGE_GROUP_CREATE_SUCCESS = 'Group was created successfully.';
    public const MESSAGE_GROUP_UPDATE_SUCCESS = 'Group was updated successfully.';
    public const MESSAGE_USER_IN_GROUP_DELETE_SUCCESS = 'The User was removed from the group.';
    public const MESSAGE_USER_IN_GROUP_DELETE_ERROR = 'User and group are not found.';
    protected const MESSAGE_GROUP_NOT_FOUND = 'Group couldn\'t be found';

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
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

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $roles = $this->getRoleTransfersFromForm($formData);

            $groupTransfer = $this->getFacade()->addGroup(
                $formData[GroupForm::FIELD_TITLE],
                $roles
            );

            $this->addSuccessMessage(static::MESSAGE_GROUP_CREATE_SUCCESS);

            return $this->redirectResponse('/acl/group/edit?' . static::PARAMETER_ID_GROUP . '=' . $groupTransfer->getIdAclGroup());
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function editAction(Request $request)
    {
        $idAclGroup = $this->castId($request->query->get(static::PARAMETER_ID_GROUP));

        $dataProvider = $this->getFactory()->createGroupFormDataProvider();

        $formData = $dataProvider->getData($idAclGroup);

        if (!$formData) {
            $this->addErrorMessage(static::MESSAGE_GROUP_NOT_FOUND);

            return $this->redirectResponse(static::GROUP_LIST_URL);
        }

        $form = $this->getFactory()
            ->createGroupForm(
                $formData,
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $roles = $this->getRoleTransfersFromForm($formData);

            $groupTransfer = $this->getFacade()->getGroup($idAclGroup);
            $groupTransfer->setName($formData[GroupForm::FIELD_TITLE]);
            $groupTransfer = $this->getFacade()->updateGroup($groupTransfer, $roles);

            $this->addSuccessMessage(static::MESSAGE_GROUP_UPDATE_SUCCESS);
            $url = sprintf('/acl/group/edit?%s=%d', static::PARAMETER_ID_GROUP, $groupTransfer->getIdAclGroup());

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
        $idGroup = $this->castId($request->query->get(static::PARAMETER_ID_GROUP));

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
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserFromGroupAction(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_DELETE)) {
            throw new MethodNotAllowedHttpException([Request::METHOD_DELETE], 'This action requires a DELETE request.');
        }

        $idGroup = $this->castId($request->request->get(static::PARAMETER_ID_GROUP));
        $idUser = $this->castId($request->request->get(static::PARAMETER_ID_USER));

        try {
            $this->getFacade()->removeUserFromGroup($idUser, $idGroup);
            $this->addSuccessMessage(static::MESSAGE_USER_IN_GROUP_DELETE_SUCCESS);
        } catch (UserAndGroupNotFoundException $e) {
            $this->addErrorMessage(static::MESSAGE_USER_IN_GROUP_DELETE_ERROR);
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
        $idGroup = $this->castId($request->get(static::PARAMETER_ID_GROUP));

        $roles = $this->getFactory()->getGroupRoleListByGroupId($idGroup);

        return $this->jsonResponse($roles);
    }
}
