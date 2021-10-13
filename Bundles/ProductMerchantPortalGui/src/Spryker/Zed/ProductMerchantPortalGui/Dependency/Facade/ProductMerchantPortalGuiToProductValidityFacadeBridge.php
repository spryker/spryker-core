<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductMerchantPortalGuiToProductValidityFacadeBridge implements ProductMerchantPortalGuiToProductValidityFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductValidity\Business\ProductValidityFacadeInterface
     */
    protected $productValidityFacade;

    /**
     * @param \Spryker\Zed\ProductValidity\Business\ProductValidityFacadeInterface $productValidityFacade
     */
    public function __construct($productValidityFacade)
    {
        $this->productValidityFacade = $productValidityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function saveProductValidity(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->productValidityFacade->saveProductValidity($productConcreteTransfer);
    }
}
