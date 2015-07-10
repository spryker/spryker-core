<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Operator;

use Generated\Shared\Cart\CartInterface;
use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use Generated\Shared\Cart\ChangeInterface;
use Psr\Log\LoggerInterface;
use SprykerFeature\Zed\Cart\Business\Model\CalculableContainer;
use SprykerFeature\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;

abstract class AbstractOperator implements OperatorInterface
{

    /**
     * @var StorageProviderInterface
     */
    protected $storageProvider;

    /**
     * @var LoggerInterface
     */
    protected $messenger;

    /**
     * @var CalculationFacade
     */
    private $cartCalculator;

    /**
     * @var ItemExpanderPluginInterface[]
     */
    private $itemExpanderPlugins = [];

    /**
     * @param StorageProviderInterface $storageProvider
     * @param CalculationFacade $cartCalculator
     * @param LoggerInterface $messenger
     */
    public function __construct(
        StorageProviderInterface $storageProvider,
        CalculationFacade $cartCalculator,
        LoggerInterface $messenger = null //@todo to be discussed
) {
        $this->storageProvider = $storageProvider;
        $this->messenger = $messenger;
        $this->cartCalculator = $cartCalculator;
    }

    /**
     * @param ChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function executeOperation(ChangeInterface $cartChange)
    {
        $changedItems = $this->expandChangedItems($cartChange);
        $cart = $this->changeCart($cartChange->getCart(), $changedItems);

        if ($this->messenger) {
            $this->messenger->info($this->createSuccessMessage());
        }

        $cart = $this->recalculate($cart);

        return $cart;
    }

    /**
     * @param CartInterface $cart
     *
     * @return CartInterface
     */
    private function recalculate(CartInterface $cart)
    {
        $calculableCart = new CalculableContainer($cart);
        $cart = $this->cartCalculator->recalculate($calculableCart);

        return $cart->getCalculableObject();
    }

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    abstract protected function changeCart(CartInterface $cart, ChangeInterface $change);

    /**
     * @return string
     */
    abstract protected function createSuccessMessage();

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    protected function expandChangedItems(ChangeInterface $change)
    {
        foreach ($this->itemExpanderPlugins as $itemExpander) {
            $change = $itemExpander->expandItems($change);
        }

        return $change;
    }

    /**
     * @param ItemExpanderPluginInterface $itemExpander
     */
    public function addItemExpanderPlugin(ItemExpanderPluginInterface $itemExpander)
    {
        $this->itemExpanderPlugins[] = $itemExpander;
    }

}
