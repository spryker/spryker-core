<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Expander;

use Generated\Shared\Transfer\ApiStoreAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;

interface StoreExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiStoreAttributesTransfer $apiStoreAttributesTransfer
     * @param array<string, mixed> $storesArray
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Generated\Shared\Transfer\ApiStoreAttributesTransfer
     */
    public function expandApiStoreAttributesTransfer(
        ApiStoreAttributesTransfer $apiStoreAttributesTransfer,
        array $storesArray,
        GlueResourceTransfer $glueResourceTransfer
    ): ApiStoreAttributesTransfer;
}
