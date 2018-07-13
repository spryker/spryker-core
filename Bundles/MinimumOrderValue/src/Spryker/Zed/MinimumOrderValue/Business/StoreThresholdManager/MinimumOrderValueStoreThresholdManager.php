<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\StoreThresholdManager;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MinimumOrderValue\Business\Exception\MinimumOrderValueStrategyGroupMismatchException;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface;
use Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface;

class MinimumOrderValueStoreThresholdManager implements MinimumOrderValueStoreThresholdManagerInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface[]
     */
    protected $minimumOrderValueStrategies;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface[] $minimumOrderValueStrategies
     * @param \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface $entityManager
     */
    public function __construct(
        array $minimumOrderValueStrategies,
        MinimumOrderValueEntityManagerInterface $entityManager
    ) {
        $this->minimumOrderValueStrategies = $minimumOrderValueStrategies;
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface $minimumOrderValueStrategy
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $value
     * @param int|null $fee
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Exception\MinimumOrderValueStrategyGroupMismatchException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function setStoreHardThreshold(
        MinimumOrderValueStrategyInterface $minimumOrderValueStrategy,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $value,
        ?int $fee = null
    ): MinimumOrderValueTransfer {
        if ($minimumOrderValueStrategy->getGroup() !== MinimumOrderValueStrategyInterface::GROUP_HARD) {
            throw new MinimumOrderValueStrategyGroupMismatchException();
        }

        return $this->entityManager
            ->setStoreThreshold(
                $minimumOrderValueStrategy,
                $storeTransfer,
                $currencyTransfer,
                $value,
                $fee
            );
    }

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface $minimumOrderValueStrategy
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $value
     * @param int|null $fee
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Exception\MinimumOrderValueStrategyGroupMismatchException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function setStoreSoftThreshold(
        MinimumOrderValueStrategyInterface $minimumOrderValueStrategy,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $value,
        ?int $fee = null
    ): MinimumOrderValueTransfer {
        if ($minimumOrderValueStrategy->getGroup() !== MinimumOrderValueStrategyInterface::GROUP_SOFT) {
            throw new MinimumOrderValueStrategyGroupMismatchException();
        }

        return $this->entityManager
            ->setStoreThreshold(
                $minimumOrderValueStrategy,
                $storeTransfer,
                $currencyTransfer,
                $value,
                $fee
            );
    }
}
