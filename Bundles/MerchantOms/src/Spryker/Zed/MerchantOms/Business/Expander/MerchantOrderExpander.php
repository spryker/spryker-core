<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\Expander;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface;

class MerchantOrderExpander implements MerchantOrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface
     */
    protected $merchantOmsRepository;

    /**
     * @param \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface $merchantOmsRepository
     */
    public function __construct(MerchantOmsRepositoryInterface $merchantOmsRepository)
    {
        $this->merchantOmsRepository = $merchantOmsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function expandMerchantOrderWithStates(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer
    {
        $merchantOrderItemIds = $this->getMerchantOrderItemIds($merchantOrderTransfer);

        return $merchantOrderTransfer->setItemStates(
            $this->merchantOmsRepository->getStateNamesByMerchantOrderItemIds($merchantOrderItemIds)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return int[]
     */
    protected function getMerchantOrderItemIds(MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $merchantOrderItemIds = [];
        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $merchantOrderItemIds[] = $merchantOrderItemTransfer->getIdMerchantOrderItem();
        }

        return $merchantOrderItemIds;
    }
}
