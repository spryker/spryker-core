<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Business\Model;

use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToProductLabelInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class PositionUpdater implements PositionUpdaterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToProductLabelInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToProductLabelInterface $productLabelFacade
     */
    public function __construct(ProductLabelGuiToProductLabelInterface $productLabelFacade)
    {
        $this->productLabelFacade = $productLabelFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransferCollection
     * @param int[] $positionMap
     *
     * @return void
     */
    public function update(array $productLabelTransferCollection, array $positionMap)
    {
        $productLabelTransferCollection = $this->updatePosition($productLabelTransferCollection, $positionMap);
        $this->storeLabels($productLabelTransferCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransferCollection
     * @param int[] $positionMap
     *
     * @return array
     */
    protected function updatePosition(array $productLabelTransferCollection, array $positionMap)
    {
        foreach ($productLabelTransferCollection as $productLabelTransfer) {
            $position = $positionMap[$productLabelTransfer->getIdProductLabel()];
            $productLabelTransfer->setPosition($position);
        }

        return $productLabelTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransferCollection
     *
     * @return void
     */
    protected function storeLabels(array $productLabelTransferCollection)
    {
        $this->handleDatabaseTransaction(function () use ($productLabelTransferCollection) {
            $this->persistLabels($productLabelTransferCollection);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransferCollection
     *
     * @return void
     */
    protected function persistLabels(array $productLabelTransferCollection)
    {
        foreach ($productLabelTransferCollection as $productLabelTransfer) {
            $this->productLabelFacade->updateLabel($productLabelTransfer);
        }
    }

}
