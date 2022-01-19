<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business\Cart\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\SalesQuantity\Persistence\SalesQuantityRepositoryInterface;

class ItemExpander implements ItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesQuantity\Persistence\SalesQuantityRepositoryInterface
     */
    protected $salesQuantityRepository;

    /**
     * @var array<\Spryker\Zed\SalesQuantityExtension\Dependency\Plugin\NonSplittableItemFilterPluginInterface>
     */
    protected $nonSplittableItemFilterPlugins;

    /**
     * @param \Spryker\Zed\SalesQuantity\Persistence\SalesQuantityRepositoryInterface $salesQuantityRepository
     * @param array<\Spryker\Zed\SalesQuantityExtension\Dependency\Plugin\NonSplittableItemFilterPluginInterface> $nonSplittableItemFilterPlugins
     */
    public function __construct(
        SalesQuantityRepositoryInterface $salesQuantityRepository,
        array $nonSplittableItemFilterPlugins
    ) {
        $this->salesQuantityRepository = $salesQuantityRepository;
        $this->nonSplittableItemFilterPlugins = $nonSplittableItemFilterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithIsQuantitySplittable(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $filteredCartChangeTransfer = $this->filterNonSplittableItems($cartChangeTransfer);

        $productConcreteSkus = $this->getSkusFromCartChangeTransfer($filteredCartChangeTransfer);
        $indexedIsQuantitySplittableData = $this
            ->salesQuantityRepository
            ->getIsProductQuantitySplittableByProductConcreteSkus($productConcreteSkus);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $isQuantitySplittable = $indexedIsQuantitySplittableData[$itemTransfer->getSku()] ?? false;
            $itemTransfer->setIsQuantitySplittable($isQuantitySplittable);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function filterNonSplittableItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $clonedCartChangeTransfer = (new CartChangeTransfer())
            ->fromArray($cartChangeTransfer->toArray());

        return $this->executeNonSplittableItemFilterPlugins($clonedCartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function executeNonSplittableItemFilterPlugins(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($this->nonSplittableItemFilterPlugins as $isQuantitySplittableFilterPlugin) {
            $cartChangeTransfer = $isQuantitySplittableFilterPlugin->filterNonSplittableItems($cartChangeTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<string>
     */
    protected function getSkusFromCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): array
    {
        $skus = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $skus[] = $itemTransfer->getSku();
        }

        return $skus;
    }
}
