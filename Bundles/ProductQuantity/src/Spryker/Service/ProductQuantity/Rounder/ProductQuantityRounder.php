<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductQuantity\Rounder;

use Generated\Shared\Transfer\ProductQuantityTransfer;
use Spryker\Service\ProductQuantity\Dependency\Service\ProductQuantityToUtilQuantityServiceInterface;
use Spryker\Service\ProductQuantity\ProductQuantityConfig;

class ProductQuantityRounder implements ProductQuantityRounderInterface
{
    /**
     * @var \Spryker\Service\ProductQuantity\ProductQuantityConfig
     */
    protected $config;

    /**
     * @var \Spryker\Service\ProductQuantity\Dependency\Service\ProductQuantityToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Service\ProductQuantity\ProductQuantityConfig $config
     * @param \Spryker\Service\ProductQuantity\Dependency\Service\ProductQuantityToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(
        ProductQuantityConfig $config,
        ProductQuantityToUtilQuantityServiceInterface $utilQuantityService
    ) {
        $this->config = $config;
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     * @param float $quantity
     *
     * @return float
     */
    public function getNearestQuantity(ProductQuantityTransfer $productQuantityTransfer, float $quantity): float
    {
        $min = $productQuantityTransfer->getQuantityMin() ?: $this->config->getDefaultMinimumQuantity();
        $max = $productQuantityTransfer->getQuantityMax();
        $interval = $productQuantityTransfer->getQuantityInterval();

        if ($quantity < $min) {
            return $min;
        }

        if ($max && $quantity > $max) {
            $quantity = $max;
        }

        $quantityMinusMin = $this->subtractQuantities($quantity, $min);

        if ($interval && !$this->isQuantityModuloEqual($quantityMinusMin, $interval)) {
            $max = $max ?? $this->sumQuantities($quantity, $interval);

            $quantity = $this->getNearestQuantityFromAllowed(
                $quantity,
                $this->getAllowedQuantities($min, $max, $interval)
            );
        }

        return $quantity;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    protected function isQuantityModuloEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->utilQuantityService->isQuantityModuloEqual($firstQuantity, $secondQuantity);
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    protected function subtractQuantities(float $firstQuantity, float $secondQuantity): float
    {
        return $this->utilQuantityService->subtractQuantities($firstQuantity, $secondQuantity);
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    protected function sumQuantities(float $firstQuantity, float $secondQuantity): float
    {
        return $this->utilQuantityService->sumQuantities($firstQuantity, $secondQuantity);
    }

    /**
     * @param float $min
     * @param float $max
     * @param float $interval
     *
     * @return float[]
     */
    protected function getAllowedQuantities(float $min, float $max, float $interval): array
    {
        if ($this->sumQuantities($min, $interval) > $max) {
            return [$min];
        }

        return array_reverse(range($min, $max, $interval));
    }

    /**
     * @param float $quantity
     * @param float[] $allowedQuantities
     *
     * @return float
     */
    protected function getNearestQuantityFromAllowed(float $quantity, array $allowedQuantities): float
    {
        if (count($allowedQuantities) === 1) {
            return reset($allowedQuantities);
        }

        $nearest = null;

        foreach ($allowedQuantities as $allowedQuantity) {
            if ($nearest === null || abs($this->subtractQuantities($quantity, $nearest)) > abs($this->subtractQuantities($allowedQuantity, $quantity))) {
                $nearest = $allowedQuantity;
            }
        }

        return $nearest ?? $quantity;
    }
}
