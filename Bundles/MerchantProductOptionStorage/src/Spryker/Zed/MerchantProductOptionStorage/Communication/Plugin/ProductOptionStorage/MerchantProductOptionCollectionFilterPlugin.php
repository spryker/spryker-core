<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Communication\Plugin\ProductOptionStorage;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOptionStorageExtension\Dependency\Plugin\ProductOptionCollectionFilterPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOptionStorage\MerchantProductOptionStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOptionStorage\Business\MerchantProductOptionStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOptionStorage\Communication\MerchantProductOptionStorageCommunicationFactory getFactory()
 */
class MerchantProductOptionCollectionFilterPlugin extends AbstractPlugin implements ProductOptionCollectionFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters merchant product option group transfers by approval status.
     * - Excludes product options with not approved merchant groups.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductOptionTransfer> $productOptionTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductOptionTransfer>
     */
    public function filter(array $productOptionTransfers): array
    {
        return $this->getFacade()->filterProductOptions($productOptionTransfers);
    }
}
