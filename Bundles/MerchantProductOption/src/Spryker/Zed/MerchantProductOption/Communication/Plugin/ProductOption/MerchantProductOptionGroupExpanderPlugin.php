<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption\Communication\Plugin\ProductOption;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionGroupExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOption\MerchantProductOptionConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOption\Communication\MerchantProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOption\Business\MerchantProductOptionFacadeInterface getFacade()
 */
class MerchantProductOptionGroupExpanderPlugin extends AbstractPlugin implements ProductOptionGroupExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands a product option group data with related merchant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function expand(ProductOptionGroupTransfer $productOptionGroupTransfer): ProductOptionGroupTransfer
    {
        return $this->getFacade()->expandProductOptionGroup($productOptionGroupTransfer);
    }
}
