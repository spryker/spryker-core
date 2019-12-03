<?php
/**
 * Created by PhpStorm.
 * User: smarovydlo
 * Date: 12/2/19
 * Time: 3:03 PM
 */

namespace Spryker\AvailabilityStorage\src\Spryker\Client\AvailabilityStorage\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductViewAvailabilityExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithAvailability(ProductViewTransfer $productViewTransfer): ProductViewTransfer;
}
