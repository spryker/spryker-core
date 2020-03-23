<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;

interface ProductLabelStorageToProductLabelBridgeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function getActiveLabelsByCriteria(ProductLabelCriteriaTransfer $productLabelCriteriaTransfer): array;
}
