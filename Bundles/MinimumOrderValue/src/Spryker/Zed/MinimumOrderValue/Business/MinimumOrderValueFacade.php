<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueBusinessFactory getFactory()
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
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface $minimumOrderValueStrategy
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $value
     * @param int|null $fee
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
        return $this->getFactory()
            ->createMinimumOrderValueStoreThresholdManager()
            ->setStoreHardThreshold(
                $minimumOrderValueStrategy,
                $storeTransfer,
                $currencyTransfer,
                $value,
                $fee
            );
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface $minimumOrderValueStrategy
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $value
     * @param int|null $fee
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
        return $this->getFactory()
            ->createMinimumOrderValueStoreThresholdManager()
            ->setStoreSoftThreshold(
                $minimumOrderValueStrategy,
                $storeTransfer,
                $currencyTransfer,
                $value,
                $fee
            );
    }
}
