<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Setter;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesReturn\Persistence\SalesReturnEntityManagerInterface;
use Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface;

class ItemRemunerationAmountSetter implements ItemRemunerationAmountSetterInterface
{
    /**
     * @var \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface
     */
    protected $salesReturnRepository;

    /**
     * @var \Spryker\Zed\SalesReturn\Persistence\SalesReturnEntityManagerInterface
     */
    protected $salesReturnEntityManager;

    /**
     * @param \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface $salesReturnRepository
     * @param \Spryker\Zed\SalesReturn\Persistence\SalesReturnEntityManagerInterface $salesReturnEntityManager
     */
    public function __construct(
        SalesReturnRepositoryInterface $salesReturnRepository,
        SalesReturnEntityManagerInterface $salesReturnEntityManager
    ) {
        $this->salesReturnRepository = $salesReturnRepository;
        $this->salesReturnEntityManager = $salesReturnEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function setOrderItemRemunerationAmount(ItemTransfer $itemTransfer): void
    {
        $itemTransfer->requireIdSalesOrderItem();

        $itemTransfer = $this->salesReturnRepository->findSalesOrderItemById($itemTransfer->getIdSalesOrderItemOrFail());

        if (!$itemTransfer) {
            return;
        }

        $itemTransfer->setRemunerationAmount($itemTransfer->getRefundableAmount());
        $this->salesReturnEntityManager->updateOrderItem($itemTransfer);
    }
}
