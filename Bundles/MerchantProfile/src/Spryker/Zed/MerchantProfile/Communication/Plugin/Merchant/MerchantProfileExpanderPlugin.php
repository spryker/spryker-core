<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Communication\Plugin\Merchant;

use Generated\Shared\Transfer\MerchantProfileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfile\Business\MerchantProfileFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProfile\Communication\MerchantProfileCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfile\MerchantProfileConfig getConfig()
 */
class MerchantProfileExpanderPlugin extends AbstractPlugin implements MerchantExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands merchant by merchant profile data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function expand(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantProfileCriteriaTransfer = $this->createMerchantProfileCriteriaTransfer($merchantTransfer);
        $merchantProfileTransfer = $this->getFacade()->findOne($merchantProfileCriteriaTransfer);

        if ($merchantProfileTransfer !== null) {
            $merchantTransfer->setMerchantProfile($merchantProfileTransfer);
        }

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer
     */
    protected function createMerchantProfileCriteriaTransfer(MerchantTransfer $merchantTransfer): MerchantProfileCriteriaTransfer
    {
        $merchantProfileCriteriaTransfer = new MerchantProfileCriteriaTransfer();
        $merchantProfileCriteriaTransfer->setFkMerchant($merchantTransfer->getIdMerchant());

        return $merchantProfileCriteriaTransfer;
    }
}
