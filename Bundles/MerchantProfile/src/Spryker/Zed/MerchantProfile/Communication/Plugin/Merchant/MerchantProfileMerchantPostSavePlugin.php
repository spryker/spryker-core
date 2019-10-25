<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Communication\Plugin\Merchant;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostSavePluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfile\Business\MerchantProfileFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProfile\MerchantProfileConfig getConfig()
 * @method \Spryker\Zed\MerchantProfile\Communication\MerchantProfileCommunicationFactory getFactory()
 */
class MerchantProfileMerchantPostSavePlugin extends AbstractPlugin implements MerchantPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Saves merchant profile after the merchant is saved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function execute(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantProfileTransfer = $merchantTransfer->getMerchantProfile();
        $merchantProfileTransfer->setFkMerchant($merchantTransfer->getIdMerchant());
        if ($merchantProfileTransfer->getIdMerchantProfile() === null) {
            $merchantProfileTransfer = $this->getFacade()->createMerchantProfile($merchantProfileTransfer);

            return $merchantTransfer->setMerchantProfile($merchantProfileTransfer);
        }

        $merchantProfileTransfer = $this->getFacade()->updateMerchantProfile($merchantProfileTransfer);

        return $merchantTransfer->setMerchantProfile($merchantProfileTransfer);
    }
}
