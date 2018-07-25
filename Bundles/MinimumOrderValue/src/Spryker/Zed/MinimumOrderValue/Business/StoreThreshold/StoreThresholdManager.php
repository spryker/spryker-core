<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\StoreThreshold;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyInvalidArgumentException;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface;

class StoreThresholdManager implements StoreThresholdManagerInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyResolverInterface
     */
    protected $minimumOrderValueStrategyResolver;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface
     */
    protected $minimumOrderValueEntityManager;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver
     * @param \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface $minimumOrderValueEntityManager
     */
    public function __construct(
        MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver,
        MinimumOrderValueEntityManagerInterface $minimumOrderValueEntityManager
    ) {
        $this->minimumOrderValueStrategyResolver = $minimumOrderValueStrategyResolver;
        $this->minimumOrderValueEntityManager = $minimumOrderValueEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyInvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function setStoreThreshold(
        MinimumOrderValueTransfer $minimumOrderValueTransfer
    ): MinimumOrderValueTransfer {
        $minimumOrderValueTransfer
            ->requireMinimumOrderValueType()
            ->requireValue();

        $minimumOrderValueTransfer
            ->getMinimumOrderValueType()
            ->requireKey();

        $minimumOrderValueStrategy = $this->minimumOrderValueStrategyResolver
            ->resolveMinimumOrderValueStrategy(
                $minimumOrderValueTransfer->getMinimumOrderValueType()->getKey()
            );

        if (!$minimumOrderValueStrategy->isValid(
            $minimumOrderValueTransfer->getValue(),
            $minimumOrderValueTransfer->getFee()
        )) {
            throw new StrategyInvalidArgumentException();
        }

        if (!$minimumOrderValueTransfer
            ->getMinimumOrderValueType()
            ->getIdMinimumOrderValueType()) {
            $minimumOrderValueTypeTransfer = $this->entityManager
                ->saveMinimumOrderValueType($minimumOrderValueStrategy->toTransfer());

            $minimumOrderValueTransfer->setMinimumOrderValueType(
                $minimumOrderValueTypeTransfer
            );
        }

        return $this->entityManager->setStoreThreshold($minimumOrderValueTransfer);
    }
}
