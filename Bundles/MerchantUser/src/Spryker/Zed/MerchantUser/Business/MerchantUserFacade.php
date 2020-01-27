<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
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
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function handleMerchantPostCreate(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantUserCreator()
            ->handleMerchantPostCreate($merchantTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $originalMerchantTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $updatedMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function handleMerchantPostUpdate(MerchantTransfer $originalMerchantTransfer, MerchantTransfer $updatedMerchantTransfer): MerchantResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantUserUpdater()
            ->handleMerchantPostUpdate($originalMerchantTransfer, $updatedMerchantTransfer);
    }
}
