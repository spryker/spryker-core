<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Pyz\Zed\Glossary\Business\GlossaryFacade;
use SprykerEngine\Zed\FlashMessenger\Business\FlashMessengerFacade;
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
     * @var FlashMessengerFacade
     */
    protected $flashMessengerFacade;

    /**
     * @var GlossaryFacade
     */
    protected $glossaryFacade;

    /**
     * @param CollectorResolver $collectorResolver
     * @param FlashMessengerFacade $flashMessengerFacade
     * @param GlossaryFacade $glossaryFacade
     */
    public function __construct(
        CollectorResolver $collectorResolver,
        FlashMessengerFacade  $flashMessengerFacade,
        GlossaryFacade $glossaryFacade
    ) {
        $this->collectorResolver = $collectorResolver;
        $this->flashMessengerFacade = $flashMessengerFacade;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param DiscountTransfer[] $discountCollection
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
        $calculatedDiscounts = $this->calculateDiscountAmount($discountCollection, $container, $settings);
        $calculatedDiscounts = $this->filterOutNonPrivilegedDiscounts($calculatedDiscounts);
        $this->distributeDiscountAmount($discountDistributor, $calculatedDiscounts);

        return $calculatedDiscounts;
    }

    /**
     * @param DiscountTransfer[] $discountCollection
     * @param CalculableInterface $container
     * @param DiscountConfigInterface $settings
     *
     * @return array
     */
    protected function calculateDiscountAmount(
        array $discountCollection,
        CalculableInterface $container,
        DiscountConfigInterface $settings
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

            $this->setSuccessfullDiscountAddMessage($discountTransfer->getDisplayName());
        }
    }

    /**
     * @param string $discountDiscplayName
     *
     * @return void
     */
    protected function setSuccessfullDiscountAddMessage($discountDiscplayName)
    {
        $message = self::DISCOUNT_SUCCESSFULLY_APPLIED_KEY;
        if ($this->glossaryFacade->hasKey(self::DISCOUNT_SUCCESSFULLY_APPLIED_KEY)) {
            $message = $this->glossaryFacade->translate(
                self::DISCOUNT_SUCCESSFULLY_APPLIED_KEY,
                ['display_name' => $discountDiscplayName]
            );
        }

        $this->flashMessengerFacade->addSuccessMessage($message);
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
