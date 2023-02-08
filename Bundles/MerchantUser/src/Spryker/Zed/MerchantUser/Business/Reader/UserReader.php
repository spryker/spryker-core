<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Reader;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;

class UserReader implements UserReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected MerchantUserToUserFacadeInterface $userFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     */
    public function __construct(MerchantUserToUserFacadeInterface $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\MerchantUser\Business\Reader\UserReader::getUserCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUser(UserCriteriaTransfer $userCriteriaTransfer): ?UserTransfer
    {
        $userConditionsTransfer = $userCriteriaTransfer->getUserConditions() ?: new UserConditionsTransfer();
        $userConditionsTransfer = $this->addConditionsFromCriteriaTransfer($userCriteriaTransfer, $userConditionsTransfer);
        $userCriteriaTransfer->setUserConditions($userConditionsTransfer);

        $userCollectionTransfer = $this->userFacade->getUserCollection($userCriteriaTransfer);

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function getUserCollection(UserCriteriaTransfer $userCriteriaTransfer): UserCollectionTransfer
    {
        return $this->userFacade->getUserCollection($userCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     * @param \Generated\Shared\Transfer\UserConditionsTransfer $userConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\UserConditionsTransfer
     */
    protected function addConditionsFromCriteriaTransfer(
        UserCriteriaTransfer $userCriteriaTransfer,
        UserConditionsTransfer $userConditionsTransfer
    ): UserConditionsTransfer {
        if ($userCriteriaTransfer->getEmail()) {
            $userConditionsTransfer->addUsername($userCriteriaTransfer->getEmailOrFail());
        }

        if ($userCriteriaTransfer->getIdUser()) {
            $userConditionsTransfer->addIdUser($userCriteriaTransfer->getIdUserOrFail());
        }

        return $userConditionsTransfer;
    }
}
