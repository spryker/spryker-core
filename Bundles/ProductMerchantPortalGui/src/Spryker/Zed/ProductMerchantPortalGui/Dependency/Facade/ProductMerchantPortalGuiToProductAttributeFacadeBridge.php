<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;

class ProductMerchantPortalGuiToProductAttributeFacadeBridge implements ProductMerchantPortalGuiToProductAttributeFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface $productAttributeFacade
     */
    public function __construct($productAttributeFacade)
    {
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer
     */
    public function getProductManagementAttributes(
        ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
    ): ProductManagementAttributeCollectionTransfer {
        return $this->productAttributeFacade->getProductManagementAttributes($productManagementAttributeFilterTransfer);
    }
}
