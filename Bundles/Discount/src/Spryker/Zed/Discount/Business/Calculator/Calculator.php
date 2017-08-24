<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;
use Spryker\Zed\Discount\Business\Exception\CalculatorException;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountAmountCalculatorPluginInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;

class Calculator implements CalculatorInterface
{

    use LoggerTrait;

    const DISCOUNT_SUCCESSFULLY_APPLIED_KEY = 'discount.successfully.applied';

    /**
     * @var array
     */
    protected $calculatedDiscounts = [];

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface
     */
    protected $collectorBuilder;

    /**
     * @var array
     */
    protected $calculatorPlugins;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\Discount\Business\Distributor\DistributorInterface
     */
    protected $distributor;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface $collectorBuilder
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface $messengerFacade
     * @param \Spryker\Zed\Discount\Business\Distributor\DistributorInterface $distributor
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[] $calculatorPlugins
     */
    public function __construct(
        SpecificationBuilderInterface $collectorBuilder,
        DiscountToMessengerInterface $messengerFacade,
        DistributorInterface $distributor,
        array $calculatorPlugins
    ) {

        $this->collectorBuilder = $collectorBuilder;
        $this->calculatorPlugins = $calculatorPlugins;
        $this->messengerFacade = $messengerFacade;
        $this->distributor = $distributor;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discounts
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer[]
     */
    public function calculate(array $discounts, QuoteTransfer $quoteTransfer)
    {
        $collectedDiscounts = $this->calculateDiscountAmount($discounts, $quoteTransfer);
        $collectedDiscounts = $this->filterExclusiveDiscounts($collectedDiscounts);
        $this->distributeDiscountAmount($collectedDiscounts);

        return $collectedDiscounts;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discounts
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer[]
     */
    protected function calculateDiscountAmount(array $discounts, QuoteTransfer $quoteTransfer)
    {
        $collectedDiscounts = [];
        foreach ($discounts as $discountTransfer) {
            $discountableItems = $this->collectItems($quoteTransfer, $discountTransfer);

            if (count($discountableItems) === 0) {
                continue;
            }

            $calculatorPlugin = $this->getCalculatorPlugin($discountTransfer);
            $discountAmount = $this->getCalculatedDiscountAmount($discountableItems, $discountTransfer, $calculatorPlugin);
            $discountTransfer->setAmount($discountAmount);

            $collectedDiscounts[] = $this->createCollectedDiscountTransfer($discountTransfer, $discountableItems);

        }

        return $collectedDiscounts;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountAmountCalculatorPluginInterface|\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface $calculatorPlugin
     *
     * @return int
     */
    protected function getCalculatedDiscountAmount(array $discountableItems, DiscountTransfer $discountTransfer, $calculatorPlugin)
    {
        if ($calculatorPlugin instanceof DiscountAmountCalculatorPluginInterface) {
            return $calculatorPlugin->calculateDiscount($discountableItems, clone $discountTransfer);
        }

        return $calculatorPlugin->calculate($discountableItems, $discountTransfer->getAmount());
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer[] $collectedDiscounts
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer[]
     */
    protected function filterExclusiveDiscounts(array $collectedDiscounts)
    {
        $collectedDiscounts = $this->sortByDiscountAmountDescending($collectedDiscounts);
        foreach ($collectedDiscounts as $collectedDiscountTransfer) {
            $discountTransfer = $collectedDiscountTransfer->getDiscount();
            if ($discountTransfer->getIsExclusive()) {
                return [$collectedDiscountTransfer];
            }
        }

        return $collectedDiscounts;
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer[] $collectedDiscountsTransfer
     *
     * @return void
     */
    protected function distributeDiscountAmount(array $collectedDiscountsTransfer)
    {
        foreach ($collectedDiscountsTransfer as $collectedDiscountTransfer) {
            $this->distributor->distributeDiscountAmountToDiscountableItems($collectedDiscountTransfer);
            $this->setSuccessfulDiscountAddMessage($collectedDiscountTransfer->getDiscount());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return void
     */
    protected function setSuccessfulDiscountAddMessage(DiscountTransfer $discountTransfer)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(self::DISCOUNT_SUCCESSFULLY_APPLIED_KEY);
        $messageTransfer->setParameters([
            'display_name' => $discountTransfer->getDisplayName(),
        ]);

        $this->messengerFacade->addSuccessMessage($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer[] $collectedDiscountsTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer[]
     */
    protected function sortByDiscountAmountDescending(array $collectedDiscountsTransfer)
    {
        usort($collectedDiscountsTransfer, function (CollectedDiscountTransfer $a, CollectedDiscountTransfer $b) {
            $amountA = (int)$a->getDiscount()->getAmount();
            $amountB = (int)$b->getDiscount()->getAmount();

            return $amountB - $amountA;
        });

        return $collectedDiscountsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    protected function collectItems(QuoteTransfer $quoteTransfer, DiscountTransfer $discountTransfer)
    {
        try {
            $collectorQueryString = $discountTransfer->getCollectorQueryString();

            $collectorComposite = $this->collectorBuilder
                ->buildFromQueryString(
                    $collectorQueryString
                );

            return $collectorComposite->collect($quoteTransfer);
        } catch (QueryStringException $exception) {
            $this->getLogger()->warning($exception->getMessage(), ['exception' => $exception]);
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\CalculatorException
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    protected function getCalculatorPlugin(DiscountTransfer $discountTransfer)
    {
        if (!isset($this->calculatorPlugins[$discountTransfer->getCalculatorPlugin()])) {
            throw new CalculatorException(
                sprintf(
                    'Calculator plugin with name "%s" not found. Did you forget to register it in "%s"::getAvailableCalculatorPlugins',
                    $discountTransfer->getCalculatorPlugin(),
                    DiscountDependencyProvider::class
                )
            );
        }

        return $this->calculatorPlugins[$discountTransfer->getCalculatorPlugin()];
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer
     */
    protected function createCollectedDiscountTransfer(DiscountTransfer $discountTransfer, array $discountableItems)
    {
        $calculatedDiscounts = new CollectedDiscountTransfer();
        $calculatedDiscounts->setDiscount($discountTransfer);
        $calculatedDiscounts->setDiscountableItems(new ArrayObject($discountableItems));

        return $calculatedDiscounts;
    }

}
