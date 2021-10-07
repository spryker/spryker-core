<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeTransfer;

class ProductAlternativePluginExecutor implements ProductAlternativePluginExecutorInterface
{
    /**
     * @var array<\Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostProductAlternativeCreatePluginInterface>
     */
    protected $postProductAlternativeCreatePlugins;

    /**
     * @var array<\Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostProductAlternativeDeletePluginInterface>
     */
    protected $postProductAlternativeDeletePlugins;

    /**
     * @param array<\Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostProductAlternativeCreatePluginInterface> $postProductAlternativeCreatePlugins
     * @param array<\Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostProductAlternativeDeletePluginInterface> $postProductAlternativeDeletePlugins
     */
    public function __construct(
        array $postProductAlternativeCreatePlugins,
        array $postProductAlternativeDeletePlugins
    ) {
        $this->postProductAlternativeDeletePlugins = $postProductAlternativeDeletePlugins;
        $this->postProductAlternativeCreatePlugins = $postProductAlternativeCreatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return void
     */
    public function executePostProductAlternativeCreatePlugins(ProductAlternativeTransfer $productAlternativeTransfer): void
    {
        foreach ($this->postProductAlternativeCreatePlugins as $postCreateProductAlternativePlugin) {
            $postCreateProductAlternativePlugin->execute($productAlternativeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return void
     */
    public function executePostProductAlternativeDeletePlugins(ProductAlternativeTransfer $productAlternativeTransfer): void
    {
        foreach ($this->postProductAlternativeDeletePlugins as $postProductAlternativeDeletePlugin) {
            $postProductAlternativeDeletePlugin->execute($productAlternativeTransfer);
        }
    }
}
