<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductsRestApi\Mapper;

use Generated\Shared\Transfer\ExecutedContentStorageTransfer;
use Generated\Shared\Transfer\RestContentAbstractProductListAttributesTransfer;
use Generated\Shared\Transfer\RestContentAbstractProductTransfer;

class ContentAbstractProductMapper implements ContentAbstractProductMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExecutedContentStorageTransfer $executedContentStorageTransfer
     * @param \Generated\Shared\Transfer\RestContentAbstractProductListAttributesTransfer $restContentAbstractProductListAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestContentAbstractProductListAttributesTransfer
     */
    public function mapExecutedContentStorageTransferToRestContentAbstractProductListAttributes(
        ExecutedContentStorageTransfer $executedContentStorageTransfer,
        RestContentAbstractProductListAttributesTransfer $restContentAbstractProductListAttributesTransfer
    ): RestContentAbstractProductListAttributesTransfer {
        foreach ($executedContentStorageTransfer->getContent() as $sku) {
            $restContentAbstractProductListAttributesTransfer->addAbstractProducts(
                $this->mapSkuToRestContentAbstractProduct($sku, new RestContentAbstractProductTransfer())
            );
        }

        return $restContentAbstractProductListAttributesTransfer;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\RestContentAbstractProductTransfer $restContentAbstractProductTransfer
     *
     * @return \Generated\Shared\Transfer\RestContentAbstractProductTransfer
     */
    public function mapSkuToRestContentAbstractProduct(
        string $sku,
        RestContentAbstractProductTransfer $restContentAbstractProductTransfer
    ): RestContentAbstractProductTransfer {
        return $restContentAbstractProductTransfer->setSku($sku);
    }
}
