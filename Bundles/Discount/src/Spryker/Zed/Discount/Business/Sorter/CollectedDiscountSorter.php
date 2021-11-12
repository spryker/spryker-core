<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Sorter;

use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;

class CollectedDiscountSorter implements CollectedDiscountSorterInterface
{
    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface
     */
    protected $discountRepository;

    /**
     * @var \Spryker\Zed\Discount\DiscountConfig
     */
    protected $discountConfig;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface $discountRepository
     * @param \Spryker\Zed\Discount\DiscountConfig $discountConfig
     */
    public function __construct(
        DiscountRepositoryInterface $discountRepository,
        DiscountConfig $discountConfig
    ) {
        $this->discountRepository = $discountRepository;
        $this->discountConfig = $discountConfig;
    }

    /**
     * @param array<\Generated\Shared\Transfer\CollectedDiscountTransfer> $collectedDiscountTransfers
     *
     * @return array<\Generated\Shared\Transfer\CollectedDiscountTransfer>
     */
    public function sort(array $collectedDiscountTransfers): array
    {
        if (count($collectedDiscountTransfers) <= 1) {
            return $collectedDiscountTransfers;
        }

        if (!$this->isSortByPriorityApplicable($collectedDiscountTransfers)) {
            return $this->sortByDiscountAmountDescending($collectedDiscountTransfers);
        }

        $collectedDiscountTransfersGroupedByPriority = $this->getCollectedDiscountsGroupedByDiscountPriority($collectedDiscountTransfers);

        return $this->sortCollectedDiscountsGroupedByPriority($collectedDiscountTransfersGroupedByPriority);
    }

    /**
     * @param array<\Generated\Shared\Transfer\CollectedDiscountTransfer> $collectedDiscountTransfers
     *
     * @return bool
     */
    protected function isSortByPriorityApplicable(array $collectedDiscountTransfers): bool
    {
        if (!$this->discountRepository->hasPriorityField()) {
            return false;
        }

        foreach ($collectedDiscountTransfers as $collectedDiscountTransfer) {
            if ($collectedDiscountTransfer->getDiscountOrFail()->getPriority() !== null) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<\Generated\Shared\Transfer\CollectedDiscountTransfer> $collectedDiscountTransfers
     *
     * @return array<int, array<int, \Generated\Shared\Transfer\CollectedDiscountTransfer>>
     */
    protected function getCollectedDiscountsGroupedByDiscountPriority(array $collectedDiscountTransfers): array
    {
        $collectedDiscountTransfersGroupedByPriority = [];
        foreach ($collectedDiscountTransfers as $collectedDiscountTransfer) {
            $discountPriority = $collectedDiscountTransfer->getDiscountOrFail()->getPriority() ?? $this->discountConfig->getPriorityMaxValue();
            $collectedDiscountTransfersGroupedByPriority[$discountPriority][] = $collectedDiscountTransfer;
        }

        return $collectedDiscountTransfersGroupedByPriority;
    }

    /**
     * @param array<int, array<int, \Generated\Shared\Transfer\CollectedDiscountTransfer>> $groupedCollectedDiscountTransfers
     *
     * @return array<\Generated\Shared\Transfer\CollectedDiscountTransfer>
     */
    protected function sortCollectedDiscountsGroupedByPriority(array $groupedCollectedDiscountTransfers): array
    {
        ksort($groupedCollectedDiscountTransfers);

        $sortedCollectedDiscountTransfers = [];
        foreach ($groupedCollectedDiscountTransfers as $collectedDiscountTransfers) {
            if (count($collectedDiscountTransfers) === 1) {
                $sortedCollectedDiscountTransfers[] = $collectedDiscountTransfers;

                continue;
            }

            $sortedCollectedDiscountTransfers[] = $this->sortByDiscountAmountDescending($collectedDiscountTransfers);
        }

        return array_merge(...$sortedCollectedDiscountTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\CollectedDiscountTransfer> $collectedDiscountTransfers
     *
     * @return array<\Generated\Shared\Transfer\CollectedDiscountTransfer>
     */
    protected function sortByDiscountAmountDescending(array $collectedDiscountTransfers): array
    {
        usort($collectedDiscountTransfers, function (CollectedDiscountTransfer $a, CollectedDiscountTransfer $b) {
            $amountA = (int)$a->getDiscountOrFail()->getAmount();
            $amountB = (int)$b->getDiscountOrFail()->getAmount();

            return $amountB - $amountA;
        });

        return $collectedDiscountTransfers;
    }
}
