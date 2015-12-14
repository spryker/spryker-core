<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Messenger\Business\MessengerFacade;
use Generated\Shared\Transfer\MessageTransfer;

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
     * @var CollectorResolver
     */
    protected $collectorResolver;

    /**
     * @var array
     */
    protected $calculatorPlugins;

    /**
     * @var MessengerFacade
     */
    protected $messengerFacade;

    /**
     * @param CollectorResolver $collectorResolver
     * @param MessengerFacade $messengerFacade
     * @param DiscountCalculatorPluginInterface[] $calculatorPlugins
     */
    public function __construct(
        CollectorResolver $collectorResolver,
        MessengerFacade  $messengerFacade,
        array $calculatorPlugins)
    {
        $this->collectorResolver = $collectorResolver;
        $this->calculatorPlugins = $calculatorPlugins;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param DiscountTransfer[] $discountCollection
     * @param QuoteTransfer $quoteTransfer
     * @param DistributorInterface $discountDistributor
     *
     * @return array
     */
    public function calculate(
        array $discountCollection,
        QuoteTransfer $quoteTransfer,
        DistributorInterface $discountDistributor
    ) {
        $calculatedDiscounts = $this->calculateDiscountAmount($discountCollection, $quoteTransfer);
        $calculatedDiscounts = $this->filterOutNonPrivilegedDiscounts($calculatedDiscounts);
        $this->distributeDiscountAmount($discountDistributor, $calculatedDiscounts);

        return $calculatedDiscounts;
    }

    /**
     * @param DiscountTransfer[] $discountCollection
     * @param QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function calculateDiscountAmount(
        array $discountCollection,
        QuoteTransfer $quoteTransfer
    ) {
        $calculatedDiscounts = [];
        foreach ($discountCollection as $discountTransfer) {
            $discountableObjects = $this->collectorResolver->collectItems($quoteTransfer, $discountTransfer);

            if (count($discountableObjects) === 0) {
                continue;
            }

            $calculatorPlugin = $this->calculatorPlugins[$discountTransfer->getCalculatorPlugin()];
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
     * @param DistributorInterface $discountDistributor
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
     * @return SpyDiscount
     */
    protected function getDiscountEntity(array $discount)
    {
        return $discount[self::KEY_DISCOUNT_TRANSFER];
    }

}
