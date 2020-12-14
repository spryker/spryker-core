<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business\Creator;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthUser\Business\Adder\AclGroupAdderInterface;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface;
use Spryker\Zed\SecurityOauthUser\Dependency\Service\SecurityOauthUserToUtilTextServiceInterface;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;

class OauthUserCreator implements OauthUserCreatorInterface
{
    protected const OAUTH_USER_CREATION_DEFAULT_PASSWORD_LENGTH = 64;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig
     */
    protected $securityOauthUserConfig;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\Dependency\Service\SecurityOauthUserToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\Business\Adder\AclGroupAdderInterface
     */
    protected $aclGroupAdder;

    /**
     * @param \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig $securityOauthUserConfig
     * @param \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\SecurityOauthUser\Dependency\Service\SecurityOauthUserToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Zed\SecurityOauthUser\Business\Adder\AclGroupAdderInterface $aclGroupAdder
     */
    public function __construct(
        SecurityOauthUserConfig $securityOauthUserConfig,
        SecurityOauthUserToUserFacadeInterface $userFacade,
        SecurityOauthUserToUtilTextServiceInterface $utilTextService,
        AclGroupAdderInterface $aclGroupAdder
    ) {
        $this->securityOauthUserConfig = $securityOauthUserConfig;
        $this->userFacade = $userFacade;
        $this->utilTextService = $utilTextService;
        $this->aclGroupAdder = $aclGroupAdder;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function createOauthUser(UserCriteriaTransfer $userCriteriaTransfer): UserTransfer
    {
        $userTransfer = $this->createUserTransfer($userCriteriaTransfer);
        $userTransfer = $this->userFacade->createUser($userTransfer);

        $this->aclGroupAdder->addOauthUserToGroup(
            $userTransfer,
            $this->securityOauthUserConfig->getOauthUserGroupReference()
        );

        return $userTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function createUserTransfer(UserCriteriaTransfer $userCriteriaTransfer): UserTransfer
    {
        $email = $userCriteriaTransfer->getEmailOrFail();

        return (new UserTransfer())
            ->setUsername($email)
            ->setFirstName($email)
            ->setLastName($email)
            ->setStatus($this->securityOauthUserConfig->getOauthUserCreationStatus())
            ->setPassword($this->utilTextService->generateRandomByteString(
                static::OAUTH_USER_CREATION_DEFAULT_PASSWORD_LENGTH
            ));
    }
}
