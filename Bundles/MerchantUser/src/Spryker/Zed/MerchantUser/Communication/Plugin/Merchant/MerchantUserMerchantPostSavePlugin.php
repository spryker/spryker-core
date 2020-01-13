<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Communication\Plugin\Merchant;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostSavePluginInterface;

/**
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 */
class MerchantUserMerchantPostSavePlugin extends AbstractPlugin implements MerchantPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates a user by provided merchant email if it doesn't already exist.
     * - Creates merchant user relation.
     * - Throws exception if the user is already connected to another merchant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @throws \Spryker\Zed\MerchantUser\Business\Exception\UserAlreadyHasMerchantException
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function execute(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantUserResponseTransfer = $this->getFacade()->createMerchantUserByMerchant($merchantTransfer);

        if ($merchantUserResponseTransfer->getIsSuccess()) {
            $merchantTransfer->setMerchantUser($merchantUserResponseTransfer->getMerchantUser());
        }

        return $merchantTransfer;
    }
}
