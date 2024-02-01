<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouseUser\Business\Provider;

use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserIdentifierTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\OauthWarehouseUser\Dependency\Facade\OauthWarehouseUserToUserFacadeInterface;
use Spryker\Zed\OauthWarehouseUser\OauthWarehouseUserConfig;

class WarehouseUserTypeOauthScopeProvider implements WarehouseUserTypeOauthScopeProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthWarehouseUser\Dependency\Facade\OauthWarehouseUserToUserFacadeInterface
     */
    protected OauthWarehouseUserToUserFacadeInterface $userFacade;

    /**
     * @var \Spryker\Zed\OauthWarehouseUser\OauthWarehouseUserConfig
     */
    protected OauthWarehouseUserConfig $oauthWarehouseUserConfig;

    /**
     * @param \Spryker\Zed\OauthWarehouseUser\Dependency\Facade\OauthWarehouseUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\OauthWarehouseUser\OauthWarehouseUserConfig $oauthWarehouseUserConfig
     */
    public function __construct(
        OauthWarehouseUserToUserFacadeInterface $userFacade,
        OauthWarehouseUserConfig $oauthWarehouseUserConfig
    ) {
        $this->userFacade = $userFacade;
        $this->oauthWarehouseUserConfig = $oauthWarehouseUserConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\UserIdentifierTransfer $userIdentifierTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(UserIdentifierTransfer $userIdentifierTransfer): array
    {
        $userTransfer = $this->findUserTransfer($userIdentifierTransfer);

        if (!$userTransfer || !$userTransfer->getIsWarehouseUser()) {
            return [];
        }

        return [
            $this->createWarehouseUserOauthScopeTransfer(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\UserIdentifierTransfer $userIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUserTransfer(UserIdentifierTransfer $userIdentifierTransfer): ?UserTransfer
    {
        $idUser = $userIdentifierTransfer->getIdUserOrFail();
        $userConditionsTransfer = (new UserConditionsTransfer())->addIdUser($idUser);
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);
        $userCollectionTransfer = $this->userFacade->getUserCollection($userCriteriaTransfer);

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }

    /**
     * @return \Generated\Shared\Transfer\OauthScopeTransfer
     */
    protected function createWarehouseUserOauthScopeTransfer(): OauthScopeTransfer
    {
        return (new OauthScopeTransfer())
            ->setIdentifier($this->oauthWarehouseUserConfig->getWarehouseUserScope());
    }
}
