<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Discount\DiscountConfigInterface;

class Calculator implements CalculatorInterface
{

    const KEY_DISCOUNT_TRANSFER = 'transfer';
    const KEY_DISCOUNT_AMOUNT = 'amount';
    const KEY_DISCOUNT_REASON = 'reason';
    const KEY_DISCOUNTABLE_OBJECTS = 'discountableObjects';
    const DISCOUNT_SUCCESSFULLY_APPLIED_KEY = 'discount.successfully.applied';

    /**
     * @var array
     */
    protected $calculatedDiscounts = [];

    /**
     * @var \Spryker\Zed\Discount\Business\Model\CollectorResolver
     */
    protected $collectorResolver;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\Discount\Business\Model\CollectorResolver $collectorResolver
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface $messengerFacade
     */
    public function __construct(
        CollectorResolver $collectorResolver,
        DiscountToMessengerInterface  $messengerFacade
    ) {
        $this->collectorResolver = $collectorResolver;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discountCollection
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Spryker\Zed\Discount\DiscountConfigInterface $config
     * @param \Spryker\Zed\Discount\Business\Distributor\DistributorInterface $discountDistributor
     *
     * @return array
     */
    public function calculate(
        array $discountCollection,
        CalculableInterface $container,
        DiscountConfigInterface $config,
        DistributorInterface $discountDistributor
    ) {
        $calculatedDiscounts = $this->calculateDiscountAmount($discountCollection, $container, $config);
        $calculatedDiscounts = $this->filterOutNonPrivilegedDiscounts($calculatedDiscounts);
        $this->distributeDiscountAmount($discountDistributor, $calculatedDiscounts);

        return $calculatedDiscounts;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discountCollection
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Spryker\Zed\Discount\DiscountConfigInterface $config
     *
     * @return array
     */
    protected function calculateDiscountAmount(
        array $discountCollection,
        CalculableInterface $container,
        DiscountConfigInterface $config
    ) {
        $calculatedDiscounts = [];
        foreach ($discountCollection as $discountTransfer) {
            $discountableObjects = $this->collectorResolver->collectItems($container, $discountTransfer);

            if (count($discountableObjects) === 0) {
                continue;
            }

            $calculatorPlugin = $config->getCalculatorPluginByName($discountTransfer->getCalculatorPlugin());
            $discountAmount = $calculatorPlugin->calculate($discountableObjects, $discountTransfer->getAmount());
            $discountTransfer->setAmount($discountAmount);

            $calculatedDiscounts[] = [
                self::KEY_DISCOUNTABLE_OBJECTS => $discountableObjects,
                self::KEY_DISCOUNT_TRANSFER => $discountTransfer,
            ];
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
     * @param \Spryker\Zed\Discount\Business\Distributor\DistributorInterface $discountDistributor
     * @param array $calculatedDiscounts
     *
     * @return void
     */
    protected function distributeDiscountAmount(DistributorInterface $discountDistributor, array $calculatedDiscounts)
    {
        foreach ($calculatedDiscounts as $calculatedDiscount) {
            /* @var $discountTransfer DiscountTransfer */
            $discountTransfer = $calculatedDiscount[self::KEY_DISCOUNT_TRANSFER];
            $discountDistributor->distribute(
                $calculatedDiscount[self::KEY_DISCOUNTABLE_OBJECTS],
                $discountTransfer
            );

            $this->setSuccessfulDiscountAddMessage($discountTransfer->getDisplayName());
        }
    }

    /**
     * @param string $discountDisplayName
     *
     * @return void
     */
    protected function setSuccessfulDiscountAddMessage($discountDisplayName)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(self::DISCOUNT_SUCCESSFULLY_APPLIED_KEY);
        $messageTransfer->setParameters(['display_name' => $discountDisplayName]);

        $this->messengerFacade->addSuccessMessage($messageTransfer);
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
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    protected function getDiscountEntity(array $discount)
    {
        return $discount[self::KEY_DISCOUNT_TRANSFER];
    }

}
