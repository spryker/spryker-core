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
 * @method \Spryker\Zed\User\Business\UserFacade getFacade()
 * @method \Spryker\Zed\User\Communication\UserCommunicationFactory getFactory()
 * @method \Spryker\Zed\User\Persistence\UserQueryContainer getQueryContainer()
 */
class EditController extends AbstractController
{
    const PARAM_ID_USER = 'id-user';
    const USER_LISTING_URL = '/user';

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

        if ($userForm->isValid()) {
            $formData = $userForm->getData();

            $userTransfer = $this->getFacade()->addUser(
                $formData[UserForm::FIELD_FIRST_NAME],
                $formData[UserForm::FIELD_LAST_NAME],
                $formData[UserForm::FIELD_USERNAME],
                $formData[UserForm::FIELD_PASSWORD]
            );

            if ($userTransfer->getIdUser()) {
                $this->addAclGroups($formData, $userTransfer);

                $this->addSuccessMessage(
                    sprintf('User with id "%d" created', $userTransfer->getIdUser())
                );

                return $this->redirectResponse(self::USER_LISTING_URL);
            }

            $this->addErrorMessage('Failed to create new user!');
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
        $idUser = $this->castId($request->get(self::PARAM_ID_USER));

        if (empty($idUser)) {
            $this->addErrorMessage('Missing user id!');

            return $this->redirectResponse(self::USER_LISTING_URL);
        }

        $dataProvider = $this->getFactory()->createUserUpdateFormDataProvider();

        $userForm = $this->getFactory()
            ->createUpdateUserForm(
                $dataProvider->getData($idUser),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($userForm->isValid()) {
            $formData = $userForm->getData();
            $userTransfer = new UserTransfer();
            $userTransfer->fromArray($formData, true);
            $userTransfer->setIdUser($idUser);
            $this->getFacade()->updateUser($userTransfer);

            $this->deleteAclGroups($idUser);
            $this->addAclGroups($formData, $userTransfer);

            $this->addSuccessMessage('User updated.');

            return $this->redirectResponse(self::USER_LISTING_URL);
        }

        return $this->viewResponse([
            'userForm' => $userForm->createView(),
            'idUser' => $idUser,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateUserAction(Request $request)
    {
        $idUser = $this->castId($request->get(self::PARAM_ID_USER));

        if (empty($idUser)) {
            $this->addErrorMessage('Missing user id!');

            return $this->redirectResponse(self::USER_LISTING_URL);
        }

        $updateStatus = $this->getFacade()->activateUser($idUser);

        if ($updateStatus) {
            $this->addSuccessMessage(sprintf('User with id "%d" successfully activated.', $idUser));
        } else {
            $this->addErrorMessage(sprintf('Failed to activate user with id "%d".', $idUser));
        }

        return $this->redirectResponse(self::USER_LISTING_URL);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateUserAction(Request $request)
    {
        $idUser = $this->castId($request->get(self::PARAM_ID_USER));

        if (empty($idUser)) {
            $this->addErrorMessage('Missing user id!');

            return $this->redirectResponse(self::USER_LISTING_URL);
        }

        $updateStatus = $this->getFacade()->deactivateUser($idUser);

        if ($updateStatus) {
            $this->addSuccessMessage(sprintf('User with id "%d" successfully deactivated.', $idUser));
        } else {
            $this->addErrorMessage(sprintf('Failed to deactivate user with id "%d".', $idUser));
        }

        return $this->redirectResponse(self::USER_LISTING_URL);
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

        $idUser = $this->castId($request->request->get(self::PARAM_ID_USER));

        if (empty($idUser)) {
            $this->addErrorMessage('Missing user id!');

            return $this->redirectResponse(self::USER_LISTING_URL);
        }

        $userTransfer = $this->getFacade()->removeUser($idUser);

        if ($userTransfer->getStatus() === SpyUserTableMap::COL_STATUS_DELETED) {
            $this->addSuccessMessage(sprintf('User with id "%d" successfully deleted.', $idUser));
        } else {
            $this->addErrorMessage(sprintf('Failed to delete user with id "%d".', $idUser));
        }

        return $this->redirectResponse(self::USER_LISTING_URL);
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
            ->createResetPasswordForm($this->getFacade())
            ->handleRequest($request);

        if ($resetPasswordForm->isValid()) {
            $formData = $resetPasswordForm->getData();
            $currentUserTransfer->setPassword(
                $formData[ResetPasswordForm::FIELD_PASSWORD]
            );

            try {
                $this->getFacade()->updateUser($currentUserTransfer);
                $this->addSuccessMessage('Password successfully updated.');
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
}
