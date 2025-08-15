<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Communication\Plugin\ProductPageSearch;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductImage\Business\ProductImageBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductImage\Communication\ProductImageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductImage\ProductImageConfig getConfig()
 * @method \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface getFacade()
 */
class ProductImageAlternativeTextProductPageDataExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `ProductPageSearchTransfer.productImages` with product image alternative texts.
     *
     * @api
     *
     * @param array<string, mixed> $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer): void
    {
        if (!$this->getConfig()->isProductImageAlternativeTextEnabled()) {
            return;
        }

        $this->getBusinessFactory()
            ->createProductPageSearchExpander()
            ->expandProductPageSearchTransferWithProductImageAlternativeTexts($productAbstractPageSearchTransfer);
    }
}
