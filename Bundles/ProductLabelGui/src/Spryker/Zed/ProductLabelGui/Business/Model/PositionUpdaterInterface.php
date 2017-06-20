<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Business\Model;

interface PositionUpdaterInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransferCollection
     * @param int[] $positionMap
     *
     * @return void
     */
    public function update(array $productLabelTransferCollection, array $positionMap);

}
