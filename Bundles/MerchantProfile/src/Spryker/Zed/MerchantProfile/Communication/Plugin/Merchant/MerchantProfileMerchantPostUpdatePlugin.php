<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Communication\Plugin\Merchant;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfile\Business\MerchantProfileFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProfile\MerchantProfileConfig getConfig()
 */
class MerchantProfileMerchantPostUpdatePlugin extends AbstractPlugin implements MerchantPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Saves merchant profile after the merchant is updated.
     * - Does not save merchant profile if MerchantTransfer.merchantProfile is not set.
     * - Creates a new merchant profile if MerchantTransfer.merchantProfile.idMerchantProfile is not set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function postUpdate(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $merchantProfileTransfer = $merchantTransfer->getMerchantProfile();
        $merchantResponseTransfer = (new MerchantResponseTransfer())->setIsSuccess(true);

        if (!$merchantProfileTransfer) {
            return $merchantResponseTransfer->setMerchant($merchantTransfer);
        }

        $merchantProfileTransfer->setFkMerchant($merchantTransfer->getIdMerchant());

        if (!$merchantProfileTransfer->getIdMerchantProfile()) {
            $merchantProfileTransfer = $this->getFacade()->createMerchantProfile($merchantProfileTransfer);

            return $merchantResponseTransfer->setMerchant($merchantTransfer->setMerchantProfile($merchantProfileTransfer));
        }

        $merchantProfileTransfer = $this->getFacade()->updateMerchantProfile($merchantProfileTransfer);

        return $merchantResponseTransfer->setMerchant($merchantTransfer->setMerchantProfile($merchantProfileTransfer));
    }
}
