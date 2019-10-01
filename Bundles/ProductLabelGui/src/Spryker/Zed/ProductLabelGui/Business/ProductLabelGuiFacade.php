<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductLabelGui\Business\ProductLabelGuiBusinessFactory getFactory()
 */
class ProductLabelGuiFacade extends AbstractFacade implements ProductLabelGuiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransferCollection
     * @param int[] $positionMap
     *
     * @return void
     */
    public function updateLabelPositions(array $productLabelTransferCollection, array $positionMap)
    {
        $this
            ->getFactory()
            ->createPositionUpdater()
            ->update($productLabelTransferCollection, $positionMap);
    }
}
