<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductsRestApi\Mapper;

use Generated\Shared\Transfer\ExecutedContentStorageTransfer;
use Generated\Shared\Transfer\RestContentAbstractProductListAttributesTransfer;

interface ContentAbstractProductMapperInterface
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
    ): RestContentAbstractProductListAttributesTransfer;
}
