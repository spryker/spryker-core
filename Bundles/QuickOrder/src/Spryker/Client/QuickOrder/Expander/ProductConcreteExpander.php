<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Expander;

class ProductConcreteExpander implements ProductConcreteExpanderInterface
{
    /**
     * @var array<\Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface>
     */
    protected $productConcreteExpanderPlugins;

    /**
     * @param array<\Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface> $productConcreteExpanderPlugins
     */
    public function __construct(array $productConcreteExpanderPlugins)
    {
        $this->productConcreteExpanderPlugins = $productConcreteExpanderPlugins;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expand(array $productConcreteTransfers): array
    {
        foreach ($this->productConcreteExpanderPlugins as $productConcreteExpanderPlugin) {
            $productConcreteTransfers = $productConcreteExpanderPlugin->expand($productConcreteTransfers);
        }

        return $productConcreteTransfers;
    }
}
