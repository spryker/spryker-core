<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Business\Provider;

use Generated\Shared\Transfer\OauthUserTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserIdentifierTransfer;
use Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToUserFacadeInterface;
use Spryker\Zed\OauthUserConnector\Dependency\Service\OauthUserConnectorToUtilEncodingServiceInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\OauthUserConnector\Dependency\Service\OauthUserConnectorToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\OauthUserConnector\Dependency\Service\OauthUserConnectorToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        OauthUserConnectorToUserFacadeInterface $userFacade,
        OauthUserConnectorToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->userFacade = $userFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getOauthUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        if (!$this->userFacade->hasActiveUserByUsername($oauthUserTransfer->getUsernameOrFail())) {
            return $oauthUserTransfer;
        }

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setEmail($oauthUserTransfer->getUsername());

        $userTransfer = $this->userFacade->findUser($userCriteriaTransfer);
        if (!$userTransfer) {
            return $oauthUserTransfer;
        }

        $isValidPassword = $this->userFacade->isValidPassword($oauthUserTransfer->getPasswordOrFail(), $userTransfer->getPasswordOrFail());
        if (!$isValidPassword) {
            return $oauthUserTransfer->setIsSuccess(false);
        }

        $userIdentifierTransfer = (new UserIdentifierTransfer())
            ->setUserReference($userTransfer->getUserReference())
            ->setIdUser($userTransfer->getIdUser());

        return $oauthUserTransfer
            ->setUserIdentifier($this->utilEncodingService->encodeJson($userIdentifierTransfer->toArray()))
            ->setIsSuccess(true);
    }
}
