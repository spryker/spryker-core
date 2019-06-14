<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Controller;

use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;
use Spryker\Zed\User\Communication\Form\ResetPasswordForm;
use Spryker\Zed\User\Communication\Form\UserForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * @method \Spryker\Zed\User\Business\UserFacadeInterface getFacade()
 * @method \Spryker\Zed\User\Communication\UserCommunicationFactory getFactory()
 * @method \Spryker\Zed\User\Persistence\UserQueryContainerInterface getQueryContainer()
 */
class EditController extends AbstractController
{
    public const PARAM_ID_USER = 'id-user';
    public const USER_LISTING_URL = '/user';

    public const MESSAGE_USER_CREATE_SUCCESS = 'User was created successfully.';
    public const MESSAGE_USER_UPDATE_SUCCESS = 'User was updated successfully.';
    public const MESSAGE_USER_ACTIVATE_SUCCESS = 'User was activated successfully.';
    public const MESSAGE_USER_DEACTIVATE_SUCCESS = 'User was deactivated successfully.';
    public const MESSAGE_USER_DELETE_SUCCESS = 'User was deleted successfully.';
    public const MESSAGE_PASSWORD_UPDATE_SUCCESS = 'User password was updated successfully.';

    public const MESSAGE_USER_CREATE_ERROR = 'User entity was not created.';
    public const MESSAGE_USER_UPDATE_ERROR = 'User entity was not updated.';
    public const MESSAGE_USER_ACTIVATE_ERROR = 'User was not activated.';
    public const MESSAGE_USER_DEACTIVATE_ERROR = 'User was not deactivated.';
    public const MESSAGE_USER_DELETE_ERROR = 'User was not deleted.';
    public const MESSAGE_ID_USER_EXTRACT_ERROR = 'Missing user id!';

    protected const MESSAGE_USER_NOT_FOUND = "User couldn't be found";

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createUserFormDataProvider();

        $userForm = $this->getFactory()
            ->createUserForm(
                [],
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        $viewData = [
            'userForm' => $userForm->createView(),
        ];

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $formData = $userForm->getData();

            $userTransfer = new UserTransfer();
            $userTransfer->fromArray($formData, true);

            $userTransfer = $this->getFacade()
                ->createUser($userTransfer);

            if ($userTransfer->getIdUser()) {
                $this->addAclGroups($formData, $userTransfer);

                $this->addSuccessMessage(static::MESSAGE_USER_CREATE_SUCCESS);

                return $this->redirectResponse(static::USER_LISTING_URL);
            }

            $this->addErrorMessage(static::MESSAGE_USER_CREATE_ERROR);
        }

        return $this->viewResponse($viewData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $idUser = $this->castId($request->get(static::PARAM_ID_USER));

        if (empty($idUser)) {
            $this->addErrorMessage(static::MESSAGE_ID_USER_EXTRACT_ERROR);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        $dataProvider = $this->getFactory()->createUserUpdateFormDataProvider();
        $providerData = $dataProvider->getData($idUser);

        if ($providerData === null) {
            $this->addErrorMessage(static::MESSAGE_USER_NOT_FOUND);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        $userForm = $this->getFactory()
            ->createUpdateUserForm(
                $providerData,
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $formData = $userForm->getData();
            $userTransfer = new UserTransfer();
            $userTransfer->fromArray($formData, true);
            $userTransfer->setIdUser($idUser);
            $this->getFacade()->updateUser($userTransfer);

            $this->deleteAclGroups($idUser);
            $this->addAclGroups($formData, $userTransfer);

            $this->addSuccessMessage(static::MESSAGE_USER_UPDATE_SUCCESS);

            return $this->redirectResponse(self::USER_LISTING_URL);
        }

        return $this->viewResponse([
            'userForm' => $userForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateUserAction(Request $request)
    {
        $idUser = $this->castId($request->get(static::PARAM_ID_USER));

        if (empty($idUser)) {
            $this->addErrorMessage(static::MESSAGE_ID_USER_EXTRACT_ERROR);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        $updateStatus = $this->getFacade()->activateUser($idUser);

        if ($updateStatus) {
            $this->addSuccessMessage(static::MESSAGE_USER_ACTIVATE_SUCCESS);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        $this->addErrorMessage(static::MESSAGE_USER_ACTIVATE_ERROR);

        return $this->redirectResponse(static::USER_LISTING_URL);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateUserAction(Request $request)
    {
        $idUser = $this->castId($request->get(static::PARAM_ID_USER));

        if (empty($idUser)) {
            $this->addErrorMessage(static::MESSAGE_ID_USER_EXTRACT_ERROR);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        if ($this->isCurrentUser($idUser)) {
            $this->addErrorMessage(static::MESSAGE_USER_DEACTIVATE_ERROR);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        $updateStatus = $this->getFacade()->deactivateUser($idUser);

        if ($updateStatus) {
            $this->addSuccessMessage(static::MESSAGE_USER_DEACTIVATE_SUCCESS);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        $this->addErrorMessage(static::MESSAGE_USER_DEACTIVATE_ERROR);

        return $this->redirectResponse(static::USER_LISTING_URL);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmDeleteAction(Request $request)
    {
        $idUser = $this->castId($request->query->get(static::PARAM_ID_USER));

        if (!$idUser) {
            $this->addErrorMessage(static::MESSAGE_ID_USER_EXTRACT_ERROR);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        if ($this->isCurrentUser($idUser)) {
            $this->addErrorMessage(static::MESSAGE_USER_DELETE_ERROR);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        $userTransfer = $this->getFacade()->findUserById($idUser);

        if (!$userTransfer) {
            $this->addErrorMessage(static::MESSAGE_USER_NOT_FOUND);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        $userDeleteConfirmForm = $this->getFactory()->getUserDeleteConfirmForm();

        return $this->viewResponse([
            'userDeleteConfirmForm' => $userDeleteConfirmForm->createView(),
            'user' => $userTransfer,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_DELETE)) {
            throw new MethodNotAllowedHttpException([Request::METHOD_DELETE], 'This action requires a DELETE request.');
        }

        $idUser = $this->castId($request->request->get(static::PARAM_ID_USER));

        if (empty($idUser)) {
            $this->addErrorMessage(static::MESSAGE_ID_USER_EXTRACT_ERROR);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        if ($this->isCurrentUser($idUser)) {
            $this->addErrorMessage(static::MESSAGE_USER_DELETE_ERROR);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        $userTransfer = $this->getFacade()->removeUser($idUser);

        if ($userTransfer->getStatus() === SpyUserTableMap::COL_STATUS_DELETED) {
            $this->addSuccessMessage(static::MESSAGE_USER_DELETE_SUCCESS);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }

        $this->addErrorMessage(static::MESSAGE_USER_DELETE_ERROR);

        return $this->redirectResponse(static::USER_LISTING_URL);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function passwordResetAction(Request $request)
    {
        $currentUserTransfer = $this->getFacade()->getCurrentUser();
        $resetPasswordForm = $this
            ->getFactory()
            ->createResetPasswordForm()
            ->handleRequest($request);

        if ($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()) {
            $formData = $resetPasswordForm->getData();
            $currentUserTransfer->setPassword(
                $formData[ResetPasswordForm::FIELD_PASSWORD]
            );

            try {
                $this->getFacade()->updateUser($currentUserTransfer);
                $this->addSuccessMessage(static::MESSAGE_PASSWORD_UPDATE_SUCCESS);
            } catch (UserNotFoundException $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        }

        return $this->viewResponse([
            'username' => $currentUserTransfer->getUsername(),
            'resetPasswordForm' => $resetPasswordForm->createView(),
        ]);
    }

    /**
     * @param array $formData
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    protected function addAclGroups(array $formData, UserTransfer $userTransfer)
    {
        if (!array_key_exists(UserForm::FIELD_GROUP, $formData)) {
            return false;
        }

        $groupPlugin = $this->getFactory()->getGroupPlugin();
        foreach ($formData[UserForm::FIELD_GROUP] as $idGroup) {
            $groupPlugin->addUserToGroup($userTransfer->getIdUser(), $idGroup);
        }

        return true;
    }

    /**
     * @param int $idUser
     *
     * @return void
     */
    protected function deleteAclGroups($idUser)
    {
        $groupPlugin = $this->getFactory()->getGroupPlugin();
        $userGroups = $groupPlugin->getUserGroups($idUser);

        foreach ($userGroups->getGroups() as $groupTransfer) {
            $groupPlugin->removeUserFromGroup($idUser, $groupTransfer->getIdAclGroup());
        }
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    protected function isCurrentUser(int $idUser): bool
    {
        $currentUser = $this->getFacade()->getCurrentUser();

        return $currentUser->getIdUser() === $idUser;
    }
}
