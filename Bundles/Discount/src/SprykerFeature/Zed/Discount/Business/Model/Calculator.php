<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Distributor\DistributorInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerFeature\Zed\Discount\DiscountConfigInterface;
use Generated\Shared\Discount\OrderInterface;

class Calculator implements CalculatorInterface
{

    const KEY_DISCOUNT_TRANSFER = 'transfer';
    const KEY_DISCOUNT_AMOUNT = 'amount';
    const KEY_DISCOUNT_REASON = 'reason';

    /**
     * @var array
     */
    protected $calculatedDiscounts = [];

    /**
     * @param DiscountInterface[] $discountCollection
     * @param CalculableInterface $container
     * @param DiscountConfigInterface $settings
     * @param DistributorInterface $distributor
     *
     * @return array
     */
    public function calculate(
        array $discountCollection,
        CalculableInterface $container,
        DiscountConfigInterface $settings,
        DistributorInterface $distributor
    ) {
        $discountableObjects = [];
        $calculatedDiscounts = [];

        foreach ($discountCollection as $discountTransfer) {
            $calculator = $settings->getCalculatorPluginByName($discountTransfer->getCalculatorPlugin());
            $collector = $settings->getCollectorPluginByName($discountTransfer->getCollectorPlugin());
            $discountableObjects = $collector->collect($discountTransfer, $container);

            $discountAmount = $calculator->calculate($discountableObjects, $discountTransfer->getAmount());
            $discountTransfer->setAmount($discountAmount);

            $calculatedDiscounts[] = [
                self::KEY_DISCOUNT_TRANSFER => $discountTransfer,
                self::KEY_DISCOUNT_AMOUNT => $discountAmount
            ];
        }

        $calculatedDiscounts = $this->filterOutNonPrivilegedDiscounts($calculatedDiscounts);

        foreach ($calculatedDiscounts as $discountTransfer) {
            $distributor->distribute(
                $discountableObjects,
                $discountTransfer[self::KEY_DISCOUNT_TRANSFER]
            );
        }

        return $calculatedDiscounts;
    }

    /**
     * @param array $calculatedDiscounts
     *
     * @return array
     */
    protected function filterOutNonPrivilegedDiscounts(array $calculatedDiscounts)
    {
        $calculatedDiscounts = $this->sortByDiscountAmountDesc($calculatedDiscounts);
        $calculatedDiscounts = $this->filterOutUnprivileged($calculatedDiscounts);

        return $calculatedDiscounts;
    }

    /**
     * @param array $calculatedDiscounts
     *
     * @return array
     */
    protected function sortByDiscountAmountDesc(array $calculatedDiscounts)
    {
        usort($calculatedDiscounts, function ($a, $b) {
            return $b[self::KEY_DISCOUNT_AMOUNT] - $a[self::KEY_DISCOUNT_AMOUNT];
        });

        return $calculatedDiscounts;
    }

    /**
     * @param array $calculatedDiscounts
     *
     * @return array
     */
    protected function filterOutUnprivileged(array $calculatedDiscounts)
    {
        $removeOtherUnprivileged = false;

        foreach ($calculatedDiscounts as $key => $discount) {
            $discountEntity = $this->getDiscountEntity($discount);
            if ($removeOtherUnprivileged && !$discountEntity->getIsPrivileged()) {
                unset($calculatedDiscounts[$key]);
                continue;
            }

            if (!$discountEntity->getIsPrivileged()) {
                $removeOtherUnprivileged = true;
            }
        }

        return $calculatedDiscounts;
    }

    /**
     * @param array $discount
     *
     * @return SpyDiscount
     */
    protected function getDiscountEntity(array $discount)
    {
        return $discount[self::KEY_DISCOUNT_TRANSFER];
    }

}
