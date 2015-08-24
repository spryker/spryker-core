<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;
use SprykerFeature\Zed\User\Business\UserFacade;
use SprykerFeature\Zed\User\Communication\UserDependencyContainer;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\User\Communication\Form\ResetPasswordForm;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;

/**
 * @method UserDependencyContainer getDependencyContainer
 * @method UserFacade getFacade()
 * @method UserQueryContainer getQueryContainer()
 */
class IndexController extends AbstractController
{

    /**
     * indexAction
     */
    public function indexAction()
    {

    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function detailsAction(Request $request)
    {
        $userId = $request->query->get('id');

        return [
            'user_id' => $userId,
        ];
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
            'resetPasswordForm' => $resetPasswordForm->createView()
        ];
    }
}
