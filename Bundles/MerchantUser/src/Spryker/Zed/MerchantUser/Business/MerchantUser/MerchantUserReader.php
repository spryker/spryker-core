<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\MerchantUser;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class MerchantUserReader implements MerchantUserReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     */
    public function __construct(
        MerchantUserRepositoryInterface $merchantUserRepository,
        MerchantUserToUserFacadeInterface $userFacade
    ) {
        $this->merchantUserRepository = $merchantUserRepository;
        $this->userFacade = $userFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function find(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): ?MerchantUserTransfer
    {
        $merchantUserTransfer = $this->merchantUserRepository->findOne($merchantUserCriteriaTransfer);

        if ($merchantUserTransfer && $merchantUserCriteriaTransfer->getWithUser()) {
            $merchantUserTransfer->setUser($this->userFacade->getUserById($merchantUserTransfer->getIdUser()));
        }

        return $merchantUserTransfer;
    }
}
