<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Distributor\DistributorInterface;
use Orm\Zed\Discount\Persistence\SpyDiscount;
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
     * @var CollectorResolver
     */
    protected $collectorResolver;

    /**
     * @param CollectorResolver $collectorResolver
     */
    public function __construct(CollectorResolver $collectorResolver)
    {
        $this->collectorResolver = $collectorResolver;
    }

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
            $discountableObjects = $this->collectorResolver->collectItems($container, $discountTransfer);

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

}
