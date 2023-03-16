<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Business\Provider;

use Generated\Shared\Transfer\OauthUserTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserIdentifierTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToUserFacadeInterface;
use Spryker\Zed\OauthUserConnector\Dependency\Service\OauthUserConnectorToUtilEncodingServiceInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     *
     * @var string
     */
    protected const USER_STATUS_ACTIVE = 'active';

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

        $userTransfer = $this->findUserTransfer($oauthUserTransfer);
        if (!$userTransfer) {
            return $oauthUserTransfer;
        }

        $isValidPassword = $this->userFacade->isValidPassword($oauthUserTransfer->getPasswordOrFail(), $userTransfer->getPasswordOrFail());
        if (!$isValidPassword) {
            return $oauthUserTransfer->setIsSuccess(false);
        }

        $userIdentifierTransfer = (new UserIdentifierTransfer())->fromArray($userTransfer->toArray(), true);

        return $oauthUserTransfer
            ->setUserIdentifier($this->utilEncodingService->encodeJson($userIdentifierTransfer->toArray()))
            ->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUserTransfer(OauthUserTransfer $oauthUserTransfer): ?UserTransfer
    {
        if (!$oauthUserTransfer->getUsername()) {
            return null;
        }

        $userCriteriaTransfer = $this->createUserCriteriaTransfer($oauthUserTransfer->getUsername());
        $userCollectionTransfer = $this->userFacade->getUserCollection($userCriteriaTransfer);

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserCriteriaTransfer
     */
    protected function createUserCriteriaTransfer(string $username): UserCriteriaTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())
            ->addStatus(static::USER_STATUS_ACTIVE)
            ->addUsername($username);

        return (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);
    }
}
