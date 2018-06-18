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
use Spryker\Zed\Discount\Exception\MissingDiscountableItemTransformerStrategyPluginException;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemTransformerStrategyPluginInterface;

class Distributor implements DistributorInterface
{
    /**
     * @var \Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemTransformerStrategyPluginInterface[]
     */
    protected $discountableItemTransformerStrategyPlugins;

    /**
     * @param \Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemTransformerStrategyPluginInterface[] $discountableItemTransformerStrategyPlugins
     */
    public function __construct(array $discountableItemTransformerStrategyPlugins)
    {
        $this->discountableItemTransformerStrategyPlugins = $discountableItemTransformerStrategyPlugins;
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
     * @throws \Spryker\Zed\Discount\Exception\MissingDiscountableItemTransformerStrategyPluginException
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
            if ($discountableItemTransformerStrategyPlugin->isApplicable($discountableItemTransfer)) {
                $discountableItemTransformerTransfer = $this->mapDiscountableItemTransformerTransfer($discountableItemTransfer, $discountTransfer, $totalDiscountAmount, $totalAmount, $quantity);
                $discountableItemTransformerStrategyPlugin->transformDiscountableItem($discountableItemTransformerTransfer);

                return;
            }
        }

        throw new MissingDiscountableItemTransformerStrategyPluginException(
            sprintf(
                'Missing instance of %s! You need to configure Distributor ' .
                'in your own DiscountDependencyProvider::getDiscountableItemTransformerStrategyPlugins() ' .
                'to be able to calculate discounts correctly.',
                DiscountableItemTransformerStrategyPluginInterface::class
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param int $totalDiscountAmount
     * @param int $totalAmount
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransformerTransfer
     */
    protected function mapDiscountableItemTransformerTransfer(
        DiscountableItemTransfer $discountableItemTransfer,
        DiscountTransfer $discountTransfer,
        int $totalDiscountAmount,
        int $totalAmount,
        int $quantity
    ): DiscountableItemTransformerTransfer {
        $discountableItemTransformerTransfer = new DiscountableItemTransformerTransfer();
        $discountableItemTransformerTransfer->setDiscountableItem($discountableItemTransfer)
            ->setDiscount($discountTransfer)
            ->setTotalDiscountAmount($totalDiscountAmount)
            ->setTotalAmount($totalAmount)
            ->setQuantity($quantity);

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
            $totalGrossAmount += $discountableItemTransfer->getUnitPrice() *
                $this->getDiscountableItemQuantity($discountableItemTransfer);
        }

        return $totalGrossAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     *
     * @return int
     */
    protected function getDiscountableItemQuantity(DiscountableItemTransfer $discountableItemTransfer): int
    {
        $quantity = 1;
        if ($discountableItemTransfer->getQuantity()) {
            $quantity = $discountableItemTransfer->getQuantity();
        }

        return $quantity;
    }
}
