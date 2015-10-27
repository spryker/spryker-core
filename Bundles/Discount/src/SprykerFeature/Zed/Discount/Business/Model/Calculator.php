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

class Calculator implements CalculatorInterface
{

    const KEY_DISCOUNT_TRANSFER = 'transfer';
    const KEY_DISCOUNT_AMOUNT = 'amount';
    const KEY_DISCOUNT_REASON = 'reason';
    const KEY_DISCOUNTABLE_OBJECTS = 'discountableObjects';

    /**
     * @var array
     */
    protected $calculatedDiscounts = [];

    /**
     * @param DiscountInterface[] $discountCollection
     * @param CalculableInterface $container
     * @param DiscountConfigInterface $settings
     * @param DistributorInterface $discountDistributor
     *
     * @return array
     */
    public function calculate(
        array $discountCollection,
        CalculableInterface $container,
        DiscountConfigInterface $settings,
        DistributorInterface $discountDistributor
    ) {
        $calculatedDiscounts = [];

        foreach ($discountCollection as $discountTransfer) {
            $discountableObjects = $this->applyDiscountCollectors($container, $settings, $discountTransfer);

            if (count($discountableObjects) === 0) {
                continue;
            }

            $calculatorPlugin = $settings->getCalculatorPluginByName($discountTransfer->getCalculatorPlugin());
            $discountAmount = $calculatorPlugin->calculate($discountableObjects, $discountTransfer->getAmount());
            $discountTransfer->setAmount($discountAmount);

            $calculatedDiscounts[] = [
                self::KEY_DISCOUNTABLE_OBJECTS => $discountableObjects,
                self::KEY_DISCOUNT_TRANSFER => $discountTransfer,
            ];
        }

        $calculatedDiscounts = $this->filterOutNonPrivilegedDiscounts($calculatedDiscounts);

        foreach ($calculatedDiscounts as $discountTransfer) {
            $discountDistributor->distribute(
                $discountTransfer[self::KEY_DISCOUNTABLE_OBJECTS],
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
            return $b[self::KEY_DISCOUNT_TRANSFER]->getAmount() - $a[self::KEY_DISCOUNT_TRANSFER]->getAmount();
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

    /**
     * @param CalculableInterface $container
     * @param DiscountConfigInterface $settings
     * @param DiscountInterface $discountTransfer
     *
     * @return DiscountableInterface[]
     */
    protected function applyDiscountCollectors(
        CalculableInterface $container,
        DiscountConfigInterface $settings,
        DiscountInterface $discountTransfer
    ) {
        $discountableObjects = [];
        foreach ($discountTransfer->getDiscountCollectors() as $discountCollectorTransfer) {
            $collectorPlugin = $settings->getCollectorPluginByName(
                $discountCollectorTransfer->getCollectorPlugin()
            );

            $collected = $collectorPlugin->collect($discountTransfer, $container, $discountCollectorTransfer);
            $discountableObjects = array_merge($discountableObjects, $collected);
        }

        $uniqDiscountableObjects = $this->getUniqDiscountableObjects($discountableObjects);

        return $uniqDiscountableObjects;
    }

    /**
     * @param DiscountableInterface[] $discountableObjects
     *
     * @return array
     */
    protected function getUniqDiscountableObjects(array $discountableObjects)
    {
        $uniqDiscountableObjects = [];
        foreach ($discountableObjects as $discountableObject) {
            $uniqDiscountableObjects[spl_object_hash($discountableObject)] = $discountableObject;
        }

        return $uniqDiscountableObjects;
    }

}
