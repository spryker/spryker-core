<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface;

class ProductViewAvailabilityExpander implements ProductViewAvailabilityExpanderInterface
{
    /**
     * @var \Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface
     */
    protected $availabilityStorageReader;

    /**
     * @var \Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\AvailabilityProviderStoragePluginInterface[]
     */
    protected $availabilityProviderStorageStrategyPlugins;

    /**
     * @param \Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface $availabilityStorageReader
     * @param \Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\AvailabilityProviderStoragePluginInterface[] $availabilityProviderStorageStrategyPlugins
     */
    public function __construct(
        AvailabilityStorageReaderInterface $availabilityStorageReader,
        array $availabilityProviderStorageStrategyPlugins
    ) {
        $this->availabilityStorageReader = $availabilityStorageReader;
        $this->availabilityProviderStorageStrategyPlugins = $availabilityProviderStorageStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithAvailability(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        $storageAvailabilityTransfer = $this->availabilityStorageReader
            ->getAvailabilityAbstractAsStorageTransfer($productViewTransfer->getIdProductAbstract());

        if (!$productViewTransfer->getIdProductConcrete()) {
            $productViewTransfer->setAvailable($storageAvailabilityTransfer->getIsAbstractProductAvailable());

            return $productViewTransfer;
        }

        $concreteProductAvailableItems = $storageAvailabilityTransfer->getConcreteProductAvailableItems();

        if (isset($concreteProductAvailableItems[$productViewTransfer->getSku()])) {
            $productViewTransfer->setAvailable($concreteProductAvailableItems[$productViewTransfer->getSku()]);
        }

        $productViewTransfer = $this->executeAvailabilityProviderStorageStrategyPlugins($productViewTransfer);

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function executeAvailabilityProviderStorageStrategyPlugins(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        foreach ($this->availabilityProviderStorageStrategyPlugins as $availabilityProviderStorageStrategyPlugin) {
            if ($availabilityProviderStorageStrategyPlugin->isApplicable($productViewTransfer)) {
                $productViewTransfer->setAvailable(
                    $availabilityProviderStorageStrategyPlugin->isProductAvailable($productViewTransfer)
                );
            }
        }

        return $productViewTransfer;
    }
}
