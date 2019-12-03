<?php
/**
 * Created by PhpStorm.
 * User: smarovydlo
 * Date: 11/26/19
 * Time: 11:55 AM
 */

namespace Spryker\AvailabilityStorage\src\Spryker\Client\AvailabilityStorage\Storage;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductViewExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, string $localeName);
}
