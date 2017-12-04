<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Availability\Plugin;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Product\Dependency\Plugin\StorageProductExpanderPluginInterface;

/**
 * @method \Spryker\Client\Availability\AvailabilityFactory getFactory()
 */
class StorageProductAvailabilityExpanderPlugin extends AbstractPlugin implements StorageProductExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     * @param array $productData
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function expandStorageProduct(StorageProductTransfer $storageProductTransfer, array $productData, $locale)
    {
        $storageAvailabilityTransfer = $this->getFactory()
            ->createAvailabilityStorage($locale)
            ->getProductAvailability($storageProductTransfer->getIdProductAbstract());

        if (!$storageProductTransfer->getIsVariant()) {
            $storageProductTransfer->setAvailable($storageAvailabilityTransfer->getIsAbstractProductAvailable());

            return $storageProductTransfer;
        }

        $concreteProductAvailableItems = $storageAvailabilityTransfer->getConcreteProductAvailableItems();
        if (isset($concreteProductAvailableItems[$storageProductTransfer->getSku()])) {
            $storageProductTransfer->setAvailable($concreteProductAvailableItems[$storageProductTransfer->getSku()]);
        }

        return $storageProductTransfer;
    }
}
