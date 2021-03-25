<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;


interface ProductMerchantPortalGuiToProductAttributeFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection();
}