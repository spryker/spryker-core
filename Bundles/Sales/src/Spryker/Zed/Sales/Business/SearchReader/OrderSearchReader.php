<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\SearchReader;

use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Zed\Sales\Business\SearchTransformer\OrderSearchFiltersTransformerInterface;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class OrderSearchReader implements OrderSearchReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @var \Spryker\Zed\Sales\Business\SearchTransformer\OrderSearchFiltersTransformerInterface
     */
    protected $orderSearchFiltersTransformer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     * @param \Spryker\Zed\Sales\Business\SearchTransformer\OrderSearchFiltersTransformerInterface $orderSearchFiltersTransformer
     */
    public function __construct(
        SalesRepositoryInterface $salesRepository,
        OrderSearchFiltersTransformerInterface $orderSearchFiltersTransformer
    ) {
        $this->salesRepository = $salesRepository;
        $this->orderSearchFiltersTransformer = $orderSearchFiltersTransformer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer = $this->orderSearchFiltersTransformer->transformOrderSearchFilters($orderListTransfer);

        return $this->salesRepository->searchOrders($orderListTransfer);
    }
}
