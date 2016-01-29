<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Controller;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;
use Spryker\Zed\User\Communication\Form\UserForm;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Spryker\Zed\User\Communication\Form\ResetPasswordForm;

/**
 * @method \Spryker\Zed\User\Business\UserFacade getFacade()
 * @method \Spryker\Zed\User\Communication\UserCommunicationFactory getFactory()
 * @method \Spryker\Zed\User\Persistence\UserQueryContainer getQueryContainer()
 */
class EditController extends AbstractController
{

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
            } else {
                $this->addErrorMessage('Failed to create new user!');
            }
        }

        return $this->viewResponse([
            'userForm' => $userForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $idUser = $request->get('id-user');

        if (empty($idUser)) {
            $this->addErrorMessage('Missing user id!');

            return $this->redirectResponse(self::USER_LISTING_URL);
        }

        $dataProvider = $this->getFactory()->createUserFormDataProvider();

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
        $idUser = $request->get('id-user');

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
        $idUser = $request->get('id-user');

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idUser = $request->get('id-user');

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
        $resetPasswordForm = $this->getFactory()->createResetPasswordForm($this->getFacade());
        $resetPasswordForm->handleRequest($request);

        if ($resetPasswordForm->isValid()) {
            $formData = $resetPasswordForm->getData();
            $currentUserTransfer->setPassword($formData[ResetPasswordForm::FIELD_PASSWORD]);

            try {
                $this->getFacade()->updateUser($currentUserTransfer);
                $this->addSuccessMessage('Password successfully updated.');
            } catch (UserNotFoundException $e) {
                $this->addErrorMessage($e->getMessage());
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

        $aclFacade = $this->getFactory()->getAclFacade();
        foreach ($formData[UserForm::FIELD_GROUP] as $idGroup) {
            $aclFacade->addUserToGroup($userTransfer->getIdUser(), $idGroup);
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
        $aclFacade = $this->getFactory()->getAclFacade();
        $userAclGroups = $aclFacade->getUserGroups($idUser);
        foreach ($userAclGroups->getGroups() as $aclGroupTransfer) {
            $aclFacade->removeUserFromGroup($idUser, $aclGroupTransfer->getIdAclGroup());
        }
    }

}
