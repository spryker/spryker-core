<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Dependency\Facade;

use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;

interface ClickAndCollectExampleToAvailabilityFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    public function areProductsSellableForStore(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer
    ): SellableItemsResponseTransfer;
}
