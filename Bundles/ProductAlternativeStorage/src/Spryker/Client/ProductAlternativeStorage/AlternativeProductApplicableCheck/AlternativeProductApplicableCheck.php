<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAlternativeStorage\AlternativeProductApplicableCheck;

use Generated\Shared\Transfer\ProductViewTransfer;

class AlternativeProductApplicableCheck implements AlternativeProductApplicableCheckInterface
{
    /**
     * @var \Spryker\Client\ProductAlternativeStorageExtension\Dependency\Plugin\AlternativeProductApplicableCheckPluginInterface[]
     */
    protected $alternativeProductApplicableCheckPlugins;

    /**
     * @param \Spryker\Client\ProductAlternativeStorageExtension\Dependency\Plugin\AlternativeProductApplicableCheckPluginInterface[] $alternativeProductApplicableCheckPlugins
     */
    public function __construct(array $alternativeProductApplicableCheckPlugins)
    {
        $this->alternativeProductApplicableCheckPlugins = $alternativeProductApplicableCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isAlternativeProductApplicable(ProductViewTransfer $productViewTransfer): bool
    {
        foreach ($this->alternativeProductApplicableCheckPlugins as $alternativeProductApplicableCheckPlugin) {
            if ($alternativeProductApplicableCheckPlugin->check($productViewTransfer)) {
                return true;
            }
        }

        return false;
    }
}
