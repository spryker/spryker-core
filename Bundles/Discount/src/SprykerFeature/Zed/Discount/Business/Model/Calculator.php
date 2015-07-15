<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount as DiscountEntity;
use SprykerFeature\Zed\Discount\Business\DiscountSettingsInterface;
use Generated\Shared\Discount\OrderInterface;

class Calculator implements
    CalculatorInterface
{

    const KEY_DISCOUNT_ENTITY = 'entity';
    const KEY_DISCOUNT_AMOUNT = 'amount';
    const KEY_DISCOUNT_REASON = 'reason';

    /**
     * @var array
     */
    protected $calculatedDiscounts = [];

    /**
     * @param DiscountEntity[] $discounts
     * @ param OrderInterface $container
     *
     * @param CalculableInterface $container
     * @param DiscountSettingsInterface $settings
     * @param DistributorInterface $distributor
     *
     * @return DiscountEntity[]
     */
    public function calculate(
        array $discounts,
        //OrderInterface $container,
        CalculableInterface $container,
        DiscountSettingsInterface $settings,
        DistributorInterface $distributor
    ) {
        $discountableObjects = [];
        $calculatedDiscounts = [];

        foreach ($discounts as $discount) {
            $calculator = $settings->getCalculatorPluginByName($discount->getCalculatorPlugin());
            $collector = $settings->getCollectorPluginByName($discount->getCollectorPlugin());
            $discountableObjects = $collector->collect($container);

            $discountAmount = $calculator->calculate($discountableObjects, $discount->getAmount());

            $calculatedDiscounts[] = [
                self::KEY_DISCOUNT_ENTITY => $discount,
                self::KEY_DISCOUNT_AMOUNT => $discountAmount,
            ];
        }

        $calculatedDiscounts = $this->filterOutNonPrivilegedDiscounts($calculatedDiscounts);

        foreach ($calculatedDiscounts as $discount) {
            $distributor->distribute($discountableObjects, $discount[self::KEY_DISCOUNT_AMOUNT]);
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
     * @return DiscountEntity
     */
    protected function getDiscountEntity(array $discount)
    {
        return $discount[self::KEY_DISCOUNT_ENTITY];
    }

}
