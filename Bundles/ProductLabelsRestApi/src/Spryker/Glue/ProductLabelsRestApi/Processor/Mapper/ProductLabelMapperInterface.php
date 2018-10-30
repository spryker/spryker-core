<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Generated\Shared\Transfer\RestProductLabelsAttributesTransfer;

interface ProductLabelMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer $productLabelDictionaryItemTransfer
     * @param \Generated\Shared\Transfer\RestProductLabelsAttributesTransfer $restProductLabelsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductLabelsAttributesTransfer
     */
    public function mapProductLabelDictionaryItemTransferToRestProductLabelsAttributesTransfer(
        ProductLabelDictionaryItemTransfer $productLabelDictionaryItemTransfer,
        RestProductLabelsAttributesTransfer $restProductLabelsAttributesTransfer
    ): RestProductLabelsAttributesTransfer;
}
