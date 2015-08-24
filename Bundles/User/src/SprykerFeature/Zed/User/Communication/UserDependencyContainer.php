<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\UserCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\User\Communication\Form\DetailsUserForm;
use SprykerFeature\Zed\User\Communication\Form\ResetPasswordRequestForm;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\User\Communication\Form\ResetPasswordForm;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;

/**
 * @method UserCommunication getFactory()
 * @method UserQueryContainer getQueryContainer()
 */
class UserDependencyContainer extends AbstractCommunicationDependencyContainer
{
    /**
     * @param Request $request
     *
     * @return DetailsUserForm
     */
    public function getDetailsUserForm(Request $request)
    {
        return $this->getFactory()->createFormDetailsUserForm($request, $this->getQueryContainer());
    }

    /**
     * @return ResetPasswordForm
     */
    public function createResetPasswordForm()
    {
        return $this->getFactory()->createFormResetPasswordForm();
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function createUserGrid(Request $request)
    {
        $queryContainer = $this->getQueryContainer();
        $users = $queryContainer->queryUsersAndGroup();

        return $this->getFactory()->createGridUserGrid($users, $request);
    }
}
