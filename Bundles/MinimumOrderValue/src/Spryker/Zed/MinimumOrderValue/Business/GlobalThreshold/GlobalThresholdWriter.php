<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\StrategyInvalidArgumentException;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface;

class GlobalThresholdWriter implements GlobalThresholdWriterInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface
     */
    protected $minimumOrderValueStrategyResolver;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface
     */
    protected $minimumOrderValueEntityManager;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver
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
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\StrategyInvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    public function setGlobalThreshold(
        GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
    ): GlobalMinimumOrderValueTransfer {
        $globalMinimumOrderValueTransfer->requireMinimumOrderValue();

        $globalMinimumOrderValueTransfer
            ->getMinimumOrderValue()
            ->getMinimumOrderValueType()
            ->requireKey();

        $minimumOrderValueStrategy = $this->minimumOrderValueStrategyResolver
            ->resolveMinimumOrderValueStrategy(
                $globalMinimumOrderValueTransfer->getMinimumOrderValue()->getMinimumOrderValueType()->getKey()
            );

        if (!$minimumOrderValueStrategy->isValid($globalMinimumOrderValueTransfer->getMinimumOrderValue())) {
            throw new StrategyInvalidArgumentException();
        }

        if (!$globalMinimumOrderValueTransfer->getMinimumOrderValue()
            ->getMinimumOrderValueType()
            ->getIdMinimumOrderValueType()
        ) {
            $minimumOrderValueTypeTransfer = $this->minimumOrderValueEntityManager
                ->saveMinimumOrderValueType($minimumOrderValueStrategy->toTransfer());

            $globalMinimumOrderValueTransfer->getMinimumOrderValue()
                ->setMinimumOrderValueType(
                    $minimumOrderValueTypeTransfer
                );
        }

        return $this->minimumOrderValueEntityManager->setGlobalThreshold($globalMinimumOrderValueTransfer);
    }
}
