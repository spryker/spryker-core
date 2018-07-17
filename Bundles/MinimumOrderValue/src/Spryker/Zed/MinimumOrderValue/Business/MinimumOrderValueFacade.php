<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueBusinessFactory getFactory()
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface getEntityManager()
 */
class MinimumOrderValueFacade extends AbstractFacade implements MinimumOrderValueFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function installMinimumOrderValueTypes(): void
    {
        $this->getFactory()
            ->createMinimumOrderValueTypeInstaller()
            ->install();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $strategyKey
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyInvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function setStoreThreshold(
        string $strategyKey,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): MinimumOrderValueTransfer {
        return $this->getFactory()
            ->createStoreThresholdManager()
            ->setStoreThreshold(
                $strategyKey,
                $storeTransfer,
                $currencyTransfer,
                $thresholdValue,
                $fee
            );
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $strategyKey
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function getMinimumOrderValueType(
        string $strategyKey
    ): MinimumOrderValueTypeTransfer {
        $minimumOrderValueStrategy = $this->getFactory()
            ->createMinimumOrderValueStrategyResolver()
            ->resolveMinimumOrderValueStrategy($strategyKey);

        return $this->getEntityManager()
            ->saveMinimumOrderValueType($minimumOrderValueStrategy->toTransfer());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $strategyKey
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     *
     * @return bool
     */
    public function validateStrategy(
        string $strategyKey,
        int $thresholdValue,
        ?int $fee = null
    ): bool {
        $minimumOrderValueStrategy = $this->getFactory()
            ->createMinimumOrderValueStrategyResolver()
            ->resolveMinimumOrderValueStrategy($strategyKey);

        return $minimumOrderValueStrategy->validate($thresholdValue, $fee);
    }
}
