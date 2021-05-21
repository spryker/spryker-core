<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\ProductPageSearch;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractMapExpanderPluginInterface;

class MerchantReferencesProductAbstractsMapExpanderPlugin implements ProductAbstractMapExpanderPluginInterface
{
    protected const KEY_MERCHANT_REFERENCES = 'merchant_references';

    /**
     * {@inheritDoc}
     * - Adds merchant references to product abstract search data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductMap(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer {
        if (empty($productData[static::KEY_MERCHANT_REFERENCES][$pageMapTransfer->getStore()])) {
            return $pageMapTransfer;
        }

        return $pageMapTransfer->setMerchantReferences($productData[static::KEY_MERCHANT_REFERENCES][$pageMapTransfer->getStore()]);
    }
}
