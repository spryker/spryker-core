<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\ProductConcreteExpander;

use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductConcreteExpander implements ProductConcreteExpanderInterface
{
    /**
     * @var \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[]
     */
    protected $productConcreteExpanderPlugins;

    /**
     * @param \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[] $productConcreteExpanderPlugins
     */
    public function __construct(array $productConcreteExpanderPlugins)
    {
        $this->productConcreteExpanderPlugins = $productConcreteExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteTransfer(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->expandProductConcreteTransferWithPlugins($productConcreteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function expandProductConcreteTransferWithPlugins(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        foreach ($this->productConcreteExpanderPlugins as $productConcreteExpanderPlugin) {
            $productConcreteTransfer = $productConcreteExpanderPlugin->expandProductConcreteTransfer($productConcreteTransfer);
        }

        return $productConcreteTransfer;
    }
}
