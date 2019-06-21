<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Distributor;

use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountableItemTransformerTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Business\Distributor\DiscountableItem\DiscountableItemTransformerInterface;
use Spryker\Zed\Discount\Dependency\Service\DiscountToUtilPriceServiceInterface;

class Distributor implements DistributorInterface
{
    /**
     * @var float
     */
    protected $roundingError = 0.0;

    /**
     * @var \Spryker\Zed\Discount\Business\Distributor\DiscountableItem\DiscountableItemTransformerInterface
     */
    protected $discountableItemTransformer;

    /**
     * @var \Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemTransformerStrategyPluginInterface[]
     */
    protected $discountableItemTransformerStrategyPlugins;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Service\DiscountToUtilPriceServiceInterface
     */
    protected $utilPriceService;

    /**
     * @param \Spryker\Zed\Discount\Business\Distributor\DiscountableItem\DiscountableItemTransformerInterface $discountableItemTransformer
     * @param array $discountableItemTransformerStrategyPlugins
     * @param \Spryker\Zed\Discount\Dependency\Service\DiscountToUtilPriceServiceInterface $utilPriceService
     */
    public function __construct(
        DiscountableItemTransformerInterface $discountableItemTransformer,
        array $discountableItemTransformerStrategyPlugins,
        DiscountToUtilPriceServiceInterface $utilPriceService
    ) {
        $this->discountableItemTransformer = $discountableItemTransformer;
        $this->discountableItemTransformerStrategyPlugins = $discountableItemTransformerStrategyPlugins;
        $this->utilPriceService = $utilPriceService;
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return void
     */
    public function distributeDiscountAmountToDiscountableItems(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        $totalAmount = $this->getTotalAmountOfDiscountableObjects($collectedDiscountTransfer);
        if ($totalAmount <= 0) {
            return;
        }

        $totalDiscountAmount = $collectedDiscountTransfer->getDiscount()->getAmount();
        if ($totalDiscountAmount <= 0) {
            return;
        }

        // There should not be a discount that is higher than the total gross price of all discountable objects
        if ($totalDiscountAmount > $totalAmount) {
            $totalDiscountAmount = $totalAmount;
        }

        foreach ($collectedDiscountTransfer->getDiscountableItems() as $discountableItemTransfer) {
            $this->transformItemsPerStrategyPlugin($discountableItemTransfer, $collectedDiscountTransfer->getDiscount(), $totalDiscountAmount, $totalAmount);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param int $totalDiscountAmount
     * @param int $totalAmount
     *
     * @return void
     */
    protected function transformItemsPerStrategyPlugin(
        DiscountableItemTransfer $discountableItemTransfer,
        DiscountTransfer $discountTransfer,
        int $totalDiscountAmount,
        int $totalAmount
    ): void {
        $quantity = $this->getDiscountableItemQuantity($discountableItemTransfer);

        foreach ($this->discountableItemTransformerStrategyPlugins as $discountableItemTransformerStrategyPlugin) {
            if (!$discountableItemTransformerStrategyPlugin->isApplicable($discountableItemTransfer)) {
                continue;
            }

            $discountableItemTransformerTransfer = $this->mapDiscountableItemTransformerTransfer($discountableItemTransfer, $discountTransfer, $totalDiscountAmount, $totalAmount, $quantity);
            $discountableItemTransformerTransfer = $discountableItemTransformerStrategyPlugin->transformDiscountableItem($discountableItemTransformerTransfer);
            $this->roundingError = $discountableItemTransformerTransfer->getRoundingError();

            return;
        }

        $this->applyTransformSplittableDiscountableItem($discountableItemTransfer, $discountTransfer, $totalDiscountAmount, $totalAmount, $quantity);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param int $totalDiscountAmount
     * @param int $totalAmount
     * @param float $quantity
     *
     * @return void
     */
    protected function applyTransformSplittableDiscountableItem(
        DiscountableItemTransfer $discountableItemTransfer,
        DiscountTransfer $discountTransfer,
        int $totalDiscountAmount,
        int $totalAmount,
        float $quantity
    ) {
        $discountableItemTransformerTransfer = $this->mapDiscountableItemTransformerTransfer($discountableItemTransfer, $discountTransfer, $totalDiscountAmount, $totalAmount, $quantity);
        $discountableItemTransformerTransfer = $this->discountableItemTransformer->transformSplittableDiscountableItem($discountableItemTransformerTransfer);
        $this->roundingError = $discountableItemTransformerTransfer->getRoundingError();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param int $totalDiscountAmount
     * @param int $totalAmount
     * @param float $quantity
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransformerTransfer
     */
    protected function mapDiscountableItemTransformerTransfer(
        DiscountableItemTransfer $discountableItemTransfer,
        DiscountTransfer $discountTransfer,
        int $totalDiscountAmount,
        int $totalAmount,
        float $quantity
    ): DiscountableItemTransformerTransfer {
        $discountableItemTransformerTransfer = new DiscountableItemTransformerTransfer();
        $discountableItemTransformerTransfer->setDiscountableItem($discountableItemTransfer)
            ->setDiscount($discountTransfer)
            ->setTotalDiscountAmount($totalDiscountAmount)
            ->setTotalAmount($totalAmount)
            ->setQuantity($quantity)
            ->setRoundingError($this->roundingError);

        return $discountableItemTransformerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return int
     */
    protected function getTotalAmountOfDiscountableObjects(CollectedDiscountTransfer $collectedDiscountTransfer): int
    {
        $totalGrossAmount = 0;
        foreach ($collectedDiscountTransfer->getDiscountableItems() as $discountableItemTransfer) {
            $grossAmount = $discountableItemTransfer->getUnitPrice() *
                $this->getDiscountableItemQuantity($discountableItemTransfer);
            $totalGrossAmount += $grossAmount;
        }

        return $this->roundPrice($totalGrossAmount);
    }

    /**
     * @param float $price
     *
     * @return int
     */
    protected function roundPrice(float $price): int
    {
        return $this->utilPriceService->roundPrice($price);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     *
     * @return float
     */
    protected function getDiscountableItemQuantity(DiscountableItemTransfer $discountableItemTransfer): float
    {
        $quantity = 1.0;
        if ($discountableItemTransfer->getQuantity()) {
            $quantity = $discountableItemTransfer->getQuantity();
        }

        return $quantity;
    }
}
