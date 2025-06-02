<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Controller;

use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 */
abstract class AbstractUserMultiFactorAuthController extends AbstractController
{
    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $formName
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getUser(Request $request, ?string $formName = null): UserTransfer
    {
        if ($this->getFactory()->getUserFacade()->hasCurrentUser() === true) {
            return $this->getFactory()->getUserFacade()->getCurrentUser();
        }

        $username = $this->getParameterFromRequest($request, UserTransfer::USERNAME, $formName);

        if ($username === null) {
            return new UserTransfer();
        }

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setUserConditions((new UserConditionsTransfer())
                ->addUsername($username));

        return $this->getFactory()->getUserFacade()->getUserCollection($userCriteriaTransfer)->getUsers()->offsetGet(0);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $parameter
     * @param string|null $formName
     *
     * @return mixed
     */
    protected function getParameterFromRequest(Request $request, string $parameter, ?string $formName = null): mixed
    {
        return $this->getFactory()->createRequestReader()->get($request, $parameter, $formName);
    }
}
