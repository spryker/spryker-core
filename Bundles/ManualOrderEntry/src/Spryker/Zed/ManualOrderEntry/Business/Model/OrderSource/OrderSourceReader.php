<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Business\Model\OrderSource;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Spryker\Zed\ManualOrderEntry\Persistence\ManualOrderEntryRepositoryInterface;

class OrderSourceReader implements OrderSourceReaderInterface
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
    public function getOrderSourceById($idOrderSource): OrderSourceTransfer
    {
        $orderSourceTransfer = $this->manualOrderEntryRepository
            ->getOrderSourceById($idOrderSource);

        return $orderSourceTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderSourceTransfer[]
     */
    public function getAllOrderSources(): array
    {
        return $this->manualOrderEntryRepository->getAllOrderSources();
    }
}
