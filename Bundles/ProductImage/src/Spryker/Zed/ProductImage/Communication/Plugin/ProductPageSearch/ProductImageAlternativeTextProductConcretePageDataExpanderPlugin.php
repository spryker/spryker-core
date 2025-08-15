<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Communication\Plugin\ProductPageSearch;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductImage\Business\ProductImageBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductImage\Communication\ProductImageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductImage\ProductImageConfig getConfig()
 * @method \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface getFacade()
 */
class ProductImageAlternativeTextProductConcretePageDataExpanderPlugin extends AbstractPlugin implements ProductConcretePageDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `ProductConcretePageSearchTransfer.images` with product image alternative texts.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function expand(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
    ): ProductConcretePageSearchTransfer {
        if (!$this->getConfig()->isProductImageAlternativeTextEnabled()) {
            return $productConcretePageSearchTransfer;
        }

        return $this->getBusinessFactory()
            ->createProductConcretePageSearchExpander()
            ->expandProductConcretePageSearchTransferWithProductImageAlternativeTexts($productConcretePageSearchTransfer);
    }
}
