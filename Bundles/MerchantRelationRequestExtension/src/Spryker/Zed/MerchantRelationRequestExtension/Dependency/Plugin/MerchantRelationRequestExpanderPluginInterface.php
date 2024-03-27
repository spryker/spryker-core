<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;

/**
 * Interface is used to expand the MerchantRelationRequest collection with additional data.
 */
interface MerchantRelationRequestExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `MerchantRelationRequestCollection` with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function expand(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer;
}
