<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Communication\SalesMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
abstract class AbstractSalesMerchantPortalGuiController extends AbstractController
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return bool
     */
    protected function isMerchantOrderBelongsCurrentMerchant(MerchantOrderTransfer $merchantOrderTransfer): bool
    {
        $currentMerchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();
        /** @var \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer */
        $merchantTransfer = $currentMerchantUserTransfer->requireMerchant()->getMerchant();

        if ($merchantTransfer->getMerchantReference() !== $merchantOrderTransfer->getMerchantReference()) {
            return false;
        }

        return true;
    }
}
