<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingList\Plugin;

use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListOrderItemGroupTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface;

class PickingListGeneratorStrategyPluginMock implements PickingListGeneratorStrategyPluginInterface
{
    /**
     * @var \Generated\Shared\Transfer\PickingListTransfer
     */
    protected PickingListTransfer $pickingListTransfer;

    /**
     * @var bool
     */
    protected bool $isApplicable;

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param bool $isApplicable
     */
    public function __construct(
        PickingListTransfer $pickingListTransfer,
        bool $isApplicable
    ) {
        $this->pickingListTransfer = $pickingListTransfer;
        $this->isApplicable = $isApplicable;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer
     *
     * @return bool
     */
    public function isApplicable(PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer): bool
    {
        return $this->isApplicable;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function generatePickingLists(PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer): PickingListCollectionTransfer
    {
        return (new PickingListCollectionTransfer())
            ->addPickingList($this->pickingListTransfer);
    }
}
