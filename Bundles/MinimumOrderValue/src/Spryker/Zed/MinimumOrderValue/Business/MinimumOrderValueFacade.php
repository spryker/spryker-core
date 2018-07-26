<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueBusinessFactory getFactory()
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface getRepository()()
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
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\StrategyNotFoundException
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\StrategyInvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function setStoreThreshold(
        MinimumOrderValueTransfer $minimumOrderValueTransfer
    ): MinimumOrderValueTransfer {
        return $this->getFactory()
            ->createStoreThresholdManager()
            ->setStoreThreshold($minimumOrderValueTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\StrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function getMinimumOrderValueType(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
    ): MinimumOrderValueTypeTransfer {
        $minimumOrderValueStrategy = $this->getFactory()
            ->createMinimumOrderValueStrategyResolver()
            ->resolveMinimumOrderValueStrategy($minimumOrderValueTypeTransfer->getKey());

        return $this->getEntityManager()
            ->saveMinimumOrderValueType($minimumOrderValueStrategy->toTransfer());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $storeId
     * @param int $currencyId
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    public function getGlobalThresholdsByStoreAndCurrency(
        int $storeId,
        int $currencyId
    ): array {
        return $this->getRepository()
            ->getGlobalThresholdsByStoreAndCurrency($storeId, $currencyId);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\StrategyNotFoundException
     *
     * @return bool
     */
    public function isStrategyValid(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): bool {
        $minimumOrderValueStrategy = $this->getFactory()
            ->createMinimumOrderValueStrategyResolver()
            ->resolveMinimumOrderValueStrategy($minimumOrderValueTypeTransfer->getKey());

        return $minimumOrderValueStrategy->isValid($thresholdValue, $fee);
    }
}
