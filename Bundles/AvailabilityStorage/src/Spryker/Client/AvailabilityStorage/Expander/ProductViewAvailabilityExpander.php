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
     * @var \Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\AvailabilityStorageStrategyPluginInterface[]
     */
    protected $availabilityStorageStrategyPlugins;

    /**
     * @param \Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface $availabilityStorageReader
     * @param \Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\AvailabilityStorageStrategyPluginInterface[] $availabilityStorageStrategyPlugins
     */
    public function __construct(
        AvailabilityStorageReaderInterface $availabilityStorageReader,
        array $availabilityStorageStrategyPlugins
    ) {
        $this->availabilityStorageReader = $availabilityStorageReader;
        $this->availabilityStorageStrategyPlugins = $availabilityStorageStrategyPlugins;
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

        $productViewTransfer = $this->executeAvailabilityStorageStrategyPlugins($productViewTransfer);

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function executeAvailabilityStorageStrategyPlugins(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        foreach ($this->availabilityStorageStrategyPlugins as $availabilityStorageStrategyPlugin) {
            if ($availabilityStorageStrategyPlugin->isApplicable($productViewTransfer)) {
                $productViewTransfer->setAvailable(
                    $availabilityStorageStrategyPlugin->isProductAvailable($productViewTransfer)
                );

                break;
            }
        }

        return $productViewTransfer;
    }
}
