<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Business\Model\OrderSource;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\ManualOrderEntry\Persistence\ManualOrderEntryRepositoryInterface;

class OrderSourceManager implements OrderSourceManagerInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntry\Persistence\ManualOrderEntryRepositoryInterface
     */
    protected $manualOrderEntryRepository;

    /**
     * @param \Spryker\Zed\ManualOrderEntry\Persistence\ManualOrderEntryRepositoryInterface $manualOrderEntryRepository
     */
    public function __construct(
        ManualOrderEntryRepositoryInterface $manualOrderEntryRepository
    ) {
        $this->manualOrderEntryRepository = $manualOrderEntryRepository;
    }

    /**
     * @param int $idOrderSource
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer
     */
    public function findOrderSourceByIdOrderSource($idOrderSource)
    {
        $orderSourceTransfer = $this->manualOrderEntryRepository
            ->getOrderSourceById($idOrderSource);

        return $orderSourceTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderSourceTransfer[]
     */
    public function getAllOrderSources()
    {
        return $this->manualOrderEntryRepository->getAllOrderSources();
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $spySalesOrderEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function hydrateOrderSource(SpySalesOrderEntityTransfer $spySalesOrderEntityTransfer, QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getOrderSource()) {
            $spySalesOrderEntityTransfer->setFkOrderSource($quoteTransfer->getOrderSource()->getIdOrderSource() ?? null);
        }

        return $spySalesOrderEntityTransfer;
    }
}
