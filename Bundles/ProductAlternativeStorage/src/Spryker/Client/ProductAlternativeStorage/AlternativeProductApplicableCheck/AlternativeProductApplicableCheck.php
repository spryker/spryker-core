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
     * @var array<\Spryker\Client\ProductAlternativeStorageExtension\Dependency\Plugin\AlternativeProductApplicablePluginInterface>
     */
    protected $alternativeProductApplicableCheckPlugins;

    /**
     * @param array<\Spryker\Client\ProductAlternativeStorageExtension\Dependency\Plugin\AlternativeProductApplicablePluginInterface> $alternativeProductApplicableCheckPlugins
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
        if ($productViewTransfer->getIdProductConcrete()) {
            return $this->executeAlternativeProductApplicableCheckCheckPlugins($productViewTransfer);
        }

        return $this->isAlternativeProductApplicableForAbstract($productViewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    protected function isAlternativeProductApplicableForAbstract(ProductViewTransfer $productViewTransfer): bool
    {
        $attributeMap = $productViewTransfer->getAttributeMap();
        if (!$attributeMap) {
            return false;
        }
        foreach ($attributeMap->getProductConcreteIds() as $concreteSku => $idProductConcrete) {
            $concreteProductViewTransfer = (new ProductViewTransfer())->fromArray($productViewTransfer->modifiedToArray(), true)
                ->setIdProductConcrete($idProductConcrete)
                ->setSku($concreteSku);
            if (!$this->executeAlternativeProductApplicableCheckCheckPlugins($concreteProductViewTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    protected function executeAlternativeProductApplicableCheckCheckPlugins(ProductViewTransfer $productViewTransfer): bool
    {
        foreach ($this->alternativeProductApplicableCheckPlugins as $alternativeProductApplicableCheckPlugin) {
            if ($alternativeProductApplicableCheckPlugin->check($productViewTransfer)) {
                return true;
            }
        }

        return false;
    }
}
