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
 */
class MerchantProfilePostSavePlugin extends AbstractPlugin implements MerchantPostSavePluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function postSave(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantProfileTransfer = $merchantTransfer->getMerchantProfile();
        $merchantProfileTransfer->setFkMerchant($merchantTransfer->getIdMerchant());

        $merchantProfileTransfer = $merchantTransfer->getMerchantProfile();
        $merchantProfileTransfer->setFkMerchant($merchantTransfer->getIdMerchant());
        $merchantProfileTransfer = $this->getFacade()->saveMerchantProfile($merchantProfileTransfer);
        $merchantTransfer->setMerchantProfile($merchantProfileTransfer);

        return $merchantTransfer;
    }
}
