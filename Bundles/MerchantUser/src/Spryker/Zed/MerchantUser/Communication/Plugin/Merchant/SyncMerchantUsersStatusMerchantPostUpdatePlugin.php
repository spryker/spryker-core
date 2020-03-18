<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Communication\Plugin\Merchant;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 */
class SyncMerchantUsersStatusMerchantPostUpdatePlugin extends AbstractPlugin implements MerchantPostUpdatePluginInterface
{
    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_DENIED
     */
    protected const MERCHANT_STATUS_DENIED = 'denied';

    /**
     * {@inheritDoc}
     * - Update users status by merchant status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function postUpdate(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        if ($merchantTransfer->getStatus() === static::MERCHANT_STATUS_DENIED) {
            $this->getFacade()->disableMerchantUsers(
                (new MerchantUserCriteriaTransfer())->setIdMerchant($merchantTransfer->getIdMerchant())
            );
        }

        return (new MerchantResponseTransfer())->setIsSuccess(true)->setMerchant($merchantTransfer);
    }
}
