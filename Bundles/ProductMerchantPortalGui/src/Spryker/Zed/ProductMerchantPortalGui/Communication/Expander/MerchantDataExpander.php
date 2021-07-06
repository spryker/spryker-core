<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;

class MerchantDataExpander implements MerchantDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade)
    {
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function expandProductAbstractWithMerchantData(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        $merchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();

        $productAbstractTransfer->setIdMerchant($merchantUserTransfer->getIdMerchant());

        return $productAbstractTransfer;
    }
}
