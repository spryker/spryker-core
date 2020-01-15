<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface getEntityManager()
 */
class MerchantUserFacade extends AbstractFacade implements MerchantUserFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function createMerchantUserByMerchant(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantUserWriter()
            ->createMerchantUserByMerchant($merchantTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function updateUserFromMerchantData(UserTransfer $userTransfer, MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantUserWriter()
            ->updateUserFromMerchantData($userTransfer, $merchantTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function getMerchantUser(MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer): ?MerchantUserTransfer
    {
        return $this->getFactory()
            ->createMerchantUserReader()
            ->getMerchantUser($merchantUserCriteriaFilterTransfer);
    }
}
