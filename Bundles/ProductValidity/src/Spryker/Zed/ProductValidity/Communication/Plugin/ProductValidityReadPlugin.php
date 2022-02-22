<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Communication\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductValidity\Communication\Plugin\Product\ProductValidityProductConcreteExpanderPlugin} instead.
 *
 * @method \Spryker\Zed\ProductValidity\Business\ProductValidityFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductValidity\ProductValidityConfig getConfig()
 */
class ProductValidityReadPlugin extends AbstractPlugin implements ProductConcretePluginReadInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function read(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        /** @phpstan-var non-empty-array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfersWithValidity */
        $productConcreteTransfersWithValidity = $this->getFacade()
            ->expandProductConcreteTransfersWithValidity([$productConcreteTransfer]);

        return array_shift($productConcreteTransfersWithValidity);
    }
}
