<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UpSellingProductsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Generated\Shared\Transfer\RestUpSellingProductsAttributesTransfer;

class UpSellingProductsResourceMapper implements UpSellingProductsResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return \Generated\Shared\Transfer\RestUpSellingProductsAttributesTransfer
     */
    public function mapUpSellingProductsTransferToRestUpSellingProductsAttributesTransfer(array $productViewTransfers): RestUpSellingProductsAttributesTransfer
    {
        $restUpSellingProductsAttributesTransfer = new RestUpSellingProductsAttributesTransfer();

        foreach ($productViewTransfers as $productViewTransfer) {
            $abstractProductsRestAttributesTransfer = new AbstractProductsRestAttributesTransfer();
            $restUpSellingProductsAttributesTransfer->addUpSellingProducts($abstractProductsRestAttributesTransfer
                ->fromArray($productViewTransfer->toArray(), true));
        }

        return $restUpSellingProductsAttributesTransfer;
    }
}
