<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Plugin\ProductStorage;

use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderByCriteriaPluginInterface;

/**
 * @method \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface getClient()
 * @method \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageFactory getFactory()
 */
class ProductViewOfferExpanderPlugin extends AbstractPlugin implements ProductViewExpanderByCriteriaPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the transfer object with the product offer reference according to provided criteria.
     *
     * @api
     *
     * @phpstan-param array<mixed> $productData
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductStorageCriteriaTransfer|null $productStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        $localeName,
        ?ProductStorageCriteriaTransfer $productStorageCriteriaTransfer = null
    ): ProductViewTransfer {
        return $this->getFactory()->createProductViewOfferExpander()->expandProductViewTransfer(
            $productViewTransfer,
            $productStorageCriteriaTransfer
        );
    }
}
