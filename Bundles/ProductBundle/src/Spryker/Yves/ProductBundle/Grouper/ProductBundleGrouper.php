<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductBundle\Grouper;

use ArrayObject;
use Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper as ClientProductBundleGrouper;

/**
 * @deprecated Use {@link \Spryker\Client\ProductBundle\ProductBundleClient} instead.
 */
class ProductBundleGrouper extends ClientProductBundleGrouper implements ProductBundleGrouperInterface
{
    /**
     * @deprecated Use {@link \Spryker\Client\ProductBundle\ProductBundleClient::getGroupedBundleItems()} instead.
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     *
     * @return array
     */
    public function getGroupedBundleItems(ArrayObject $items, ArrayObject $bundleItems)
    {
        return parent::getGroupedBundleItems($items, $bundleItems);
    }
}
