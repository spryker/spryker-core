<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Controller;

use Generated\Shared\Transfer\UserTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;
use SprykerFeature\Zed\User\Business\UserFacade;
use SprykerFeature\Zed\User\Communication\Form\UserForm;
use SprykerFeature\Zed\User\Communication\UserDependencyContainer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\User\Communication\Form\UserCreateForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SprykerFeature\Zed\User\Communication\Form\ResetPasswordForm;

/**
 * @method UserFacade getFacade()
 * @method UserDependencyContainer getDependencyContainer()
 * @method UserQueryContainer getQueryContainer()
 */
class EditController extends AbstractController
{

    const USER_LISTING_URL = '/user';

    /**
     * @return array
     */
    public function createAction()
    {
        $userForm = $this->getDependencyContainer()->createUserForm();
        $userForm->handleRequest();

        if ($userForm->isValid()) {
            $formData = $userForm->getData();
            $userTransfer = $this->getFacade()->addUser(
                $formData[UserCreateForm::FIRST_NAME],
                $formData[UserCreateForm::LAST_NAME],
                $formData[UserCreateForm::USERNAME],
                $formData[UserCreateForm::PASSWORD]
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

        return [
            'userForm' => $userForm->createView(),
        ];
    }

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $idUser = $request->get('id-user');
        if (empty($idUser)) {
            $this->addErrorMessage('Missing user id!');

            return $this->redirectResponse(self::USER_LISTING_URL);
        }

        $userForm = $this->getDependencyContainer()->createUpdateUserForm($idUser);
        $userForm->handleRequest();

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

        return [
            'userForm' => $userForm->createView(),
            'idUser' => $idUser,
        ];
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
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
     * @param Request $request
     *
     * @return RedirectResponse
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
     * @param Request $request
     *
     * @return RedirectResponse
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
     * @return array
     */
    public function passwordResetAction()
    {
        $currentUserTransfer = $this->getFacade()->getCurrentUser();
        $resetPasswordForm = $this->getDependencyContainer()->createResetPasswordForm();
        $resetPasswordForm->handleRequest();

        if ($resetPasswordForm->isValid()) {
            $formData = $resetPasswordForm->getData();
            $currentUserTransfer->setPassword($formData[ResetPasswordForm::PASSWORD]);

            try {
                $this->getFacade()->updateUser($currentUserTransfer);
                $this->addSuccessMessage('Password successfully updated.');
            } catch (UserNotFoundException $e) {
                $this->addErrorMessage($e->getMessage());
            }
        }

        return [
            'resetPasswordForm' => $resetPasswordForm->createView(),
        ];
    }

    /**
     * @param array $formData
     * @param UserTransfer $userTransfer
     *
     * @return bool
     */
    protected function addAclGroups(array $formData, UserTransfer $userTransfer)
    {
        if (!array_key_exists(UserForm::GROUP, $formData)) {
            return false;
        }

        $aclFacade = $this->getDependencyContainer()->createAclFacade();
        foreach ($formData[UserForm::GROUP] as $idGroup) {
            $aclFacade->addUserToGroup($userTransfer->getIdUser(), $idGroup);
        }

        return true;
    }

    /**
     * @param int $idUser
     */
    protected function deleteAclGroups($idUser)
    {
        $aclFacade = $this->getDependencyContainer()->createAclFacade();
        $userAclGroups = $aclFacade->getUserGroups($idUser);
        foreach ($userAclGroups->getGroups() as $aclGroupTransfer) {
            $aclFacade->removeUserFromGroup($idUser, $aclGroupTransfer->getIdAclGroup());
        }
    }

}
