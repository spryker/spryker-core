<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\Expander;

use Generated\Shared\Transfer\ButtonCollectionTransfer;

interface ProductListButtonsExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    public function expandButtonCollection(ButtonCollectionTransfer $buttonCollectionTransfer): ButtonCollectionTransfer;
}
